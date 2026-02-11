<?php

// ============================================
// SUJET CONCRET : News
// ============================================

/**
 * News implémente SplSubject (interface PHP native pour le pattern Observer)
 * Rôle : Maintenir la liste des observateurs et les notifier lors de changements
 */
class News implements SplSubject
{
    private array $news = [];
    private array $observers = [];

    /**
     * Attache un observateur à la liste des abonnés
     * L'observateur sera notifié à chaque nouvelle actualité
     */
    public function attach(SplObserver $observer): void
    {
        $this->observers[] = $observer;
        echo "✅ " . $observer->getName() . " s'est abonné aux actualités\n";
    }

    /**
     * Détache un observateur de la liste des abonnés
     * L'observateur ne recevra plus de notifications
     */
    public function detach(SplObserver $observer): void
    {
        $this->observers = array_filter($this->observers, function ($obs) use ($observer) {
            return $obs !== $observer;
        });
        echo "❌ " . $observer->getName() . " s'est désabonné des actualités\n";
    }

    /**
     * Notifie tous les observateurs attachés
     * Appelé automatiquement quand une nouvelle actualité est ajoutée
     */
    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * Ajoute une nouvelle actualité et déclenche la notification
     * C'est ici que le pattern Observer montre sa puissance :
     * pas besoin d'appeler manuellement chaque observateur
     */
    public function addNews(string $news): void
    {
        $this->news[] = $news;
        echo "\n📰 Nouvelle actualité publiée : \"$news\"\n";
        echo "🔔 Notification en cours...\n\n";
        $this->notify(); // Notification automatique de tous les abonnés
    }

    /**
     * Retourne la dernière actualité publiée
     */
    public function getNews(): string
    {
        return end($this->news);
    }
}

// ============================================
// OBSERVATEUR CONCRET : User
// ============================================

/**
 * User implémente SplObserver (interface PHP native)
 * Rôle : Définir comment réagir aux notifications du sujet
 */
class User implements SplObserver
{
    private string $name;
    private string $notificationType;

    /**
     * @param string $name Nom de l'utilisateur
     * @param string $notificationType Type de notification (Email, SMS, Push)
     */
    public function __construct(string $name, string $notificationType = "Email")
    {
        $this->name = $name;
        $this->notificationType = $notificationType;
    }

    /**
     * Méthode appelée automatiquement par le sujet lors d'une notification
     * Chaque utilisateur peut réagir différemment selon son type de notification
     */
    public function update(SplSubject $subject): void
    {
        $icon = match ($this->notificationType) {
            "Email" => "📧",
            "SMS" => "📱",
            "Push" => "🔔",
            default => "📬"
        };

        echo "$icon [$this->notificationType] {$this->name} a reçu : \"{$subject->getNews()}\"\n";
    }

    public function getName(): string
    {
        return $this->name;
    }
}

// ============================================
// DÉMONSTRATION DU PATTERN
// ============================================

echo "========================================\n";
echo "   DÉMONSTRATION DU PATTERN OBSERVER\n";
echo "========================================\n\n";

$newsAgency = new News();

$tom = new User("Tom", "Email");
$benjamin = new User("Benjamin", "SMS");
$alice = new User("Alice", "Push");

echo "\n--- Phase 1 : Abonnements ---\n";
$newsAgency->attach($tom);
$newsAgency->attach($benjamin);
$newsAgency->attach($alice);

echo "\n--- Phase 2 : Publication d'actualités ---\n";
$newsAgency->addNews("Le pattern Observer simplifie les notifications !");

echo "\n--- Phase 3 : Désabonnement ---\n";
$newsAgency->detach($benjamin);

echo "\n--- Phase 4 : Nouvelle publication ---\n";
$newsAgency->addNews("PHP 8.5 est sorti avec de nouvelles fonctionnalités !");

echo "\n========================================\n";
echo "   FIN DE LA DÉMONSTRATION\n";
echo "========================================\n";