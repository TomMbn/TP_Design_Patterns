<?php

// ============================================
// RECEIVER (Le Destinataire)
// ============================================

/**
 * L'objet métier sur lequel les actions (commandes) vont opérer.
 * Il contient la vraie logique métier complexe.
 */
class BankAccount
{
    private string $ownerName;
    private int $balanceAmount;

    public function __construct(string $ownerName, int $initialBalance = 0)
    {
        $this->ownerName = $ownerName;
        $this->balanceAmount = $initialBalance;
    }

    public function deposit(int $amount): void
    {
        $this->balanceAmount += $amount;
        echo "[BANQUE] ++ Dépôt de {$amount}€ sur le compte de {$this->ownerName}.\n";
    }

    public function withdraw(int $amount): bool
    {
        if ($this->balanceAmount >= $amount) {
            $this->balanceAmount -= $amount;
            echo "[BANQUE] -- Retrait de {$amount}€ sur le compte de {$this->ownerName}.\n";
            return true;
        } else {
            echo "[BANQUE] ❌ Retrait refusé pour {$this->ownerName} (Fonds insuffisants : {$amount}€ demandés).\n";
            return false;
        }
    }

    public function getBalance(): int
    {
        return $this->balanceAmount;
    }
}

// ============================================
// COMMAND INTERFACE
// ============================================

/**
 * L'interface déclarant l'exécution ET l'annulation possible.
 */
interface TransactionCommand
{
    public function execute(): bool;
    public function undo(): void;
}

// ============================================
// CONCRETE COMMANDS
// ============================================

/**
 * Commande concrète pour un Dépôt
 */
class DepositCommand implements TransactionCommand
{
    private BankAccount $account;
    private int $amount;

    public function __construct(BankAccount $account, int $amount)
    {
        $this->account = $account;
        $this->amount = $amount;
    }

    public function execute(): bool
    {
        $this->account->deposit($this->amount);
        return true; // Un dépôt réussit toujours ici
    }

    public function undo(): void
    {
        echo "[UNDO] Annulation du dépôt de {$this->amount}€...\n";
        $this->account->withdraw($this->amount);
    }
}

/**
 * Commande concrète pour un Retrait
 */
class WithdrawCommand implements TransactionCommand
{
    private BankAccount $account;
    private int $amount;

    public function __construct(BankAccount $account, int $amount)
    {
        $this->account = $account;
        $this->amount = $amount;
    }

    public function execute(): bool
    {
        return $this->account->withdraw($this->amount);
    }

    public function undo(): void
    {
        echo "[UNDO] Annulation du retrait de {$this->amount}€...\n";
        $this->account->deposit($this->amount);
    }
}

// ============================================
// INVOKER (L'Invocateur)
// ============================================

/**
 * Le système transactionnel qui demande l'exécution des commandes
 * et garde un historique pour pouvoir faire du "Annuler" (Undo).
 */
class TransactionManager
{
    /** @var TransactionCommand[] */
    private array $history = [];

    /**
     * Prend une commande, l'exécute, et l'ajoute à l'historique si réussie.
     */
    public function executeTransaction(TransactionCommand $command): void
    {
        $success = $command->execute();

        // On ne stocke dans l'historique que les commandes qui ont réussi
        // (pour ne pas "annuler" un retrait qui n'a pas pu se faire).
        if ($success) {
            $this->history[] = $command;
        }
    }

    /**
     * Annule la toute dernière commande stockée dans l'historique.
     */
    public function undoLastTransaction(): void
    {
        if (empty($this->history)) {
            echo "ℹ️ Aucune transaction récente à annuler.\n";
            return;
        }

        /** @var TransactionCommand $lastCommand */
        $lastCommand = array_pop($this->history); // Récupère et supprime la dernière
        $lastCommand->undo();
    }
}

// ============================================
// CLIENT / DÉMONSTRATION
// ============================================

echo "========================================\n";
echo "   PATTERN COMMAND\n";
echo "   Système Transactionnel Bancaire (Undo/Redo)\n";
echo "========================================\n\n";

// 1. Initialisation de la machinerie
$bankApp = new TransactionManager(); // Invoker
$account = new BankAccount("Tom", 100); // Receiver

echo "--- ÉTAT INITIAL : Compte avec {$account->getBalance()}€ ---\n\n";

// 2. Le client veut faire des actions. Il crée des "Commandes" encapsulées.
$action1 = new DepositCommand($account, 50);
$action2 = new WithdrawCommand($account, 20);
$action3 = new WithdrawCommand($account, 500); // Ne passera pas (fonds insuffisant)

// 3. On donne les commandes à l'Invoker pour exécution (comme appuyer sur un bouton)
echo ">>> Exécution des transactions...\n";
$bankApp->executeTransaction($action1);
$bankApp->executeTransaction($action2);
$bankApp->executeTransaction($action3);

echo "\n--- NOUVEL ÉTAT : Compte avec {$account->getBalance()}€ ---\n\n";

// 4. Oh mince, on veut annuler les dernières actions ! (UNDO)
echo ">>> Le client clique sur 'Annuler (Ctrl+Z)' deux fois !\n";
$bankApp->undoLastTransaction(); // Annule le retrait de 20
$bankApp->undoLastTransaction(); // Annule le dépôt de 50
$bankApp->undoLastTransaction(); // Essaye d'annuler une 3e fois pour montrer que l'historique est vide

echo "\n--- ÉTAT FINAL : Compte restauré à {$account->getBalance()}€ ---\n\n";

echo "✅ Le pattern Command et son historique ont permis d'annuler dynamiquement des actions passées sur une logique métier sans rien casser !\n";
