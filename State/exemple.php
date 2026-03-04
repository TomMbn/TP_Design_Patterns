<?php

// ============================================
// STATE INTERFACE (L'interface des États)
// ============================================

/**
 * Interface commune pour tous les États possibles de l'Alarme.
 * Gère tous les événements/actions qui peuvent se produire.
 */
interface AlarmState
{
    /** Le propriétaire entre le code pour armer le système */
    public function armMode(): void;

    /** Le propriétaire entre le code pour désarmer le système */
    public function disarmMode(): void;

    /** Un capteur physique (mouvement/porte) détecte quelque chose */
    public function triggerSensor(): void;
}

// ============================================
// CONTEXT (L'objet de base contenant l'état)
// ============================================

/**
 * Le "Contexte" avec lequel les clients (le clavier de la maison, les capteurs) interagissent.
 * Il délègue toute sa logique à son état actuel.
 */
class SmartAlarmSystem
{
    private AlarmState $currentState;

    public function __construct()
    {
        // État initial quand on installe le système
        $this->transitionTo(new DisarmedState($this));
    }

    /**
     * Permet aux objets d'État de changer l'état global du Contexte.
     */
    public function transitionTo(AlarmState $state): void
    {
        // On récupère le nom de la classe pour un log joli (ex: DisarmedState -> Disarmed)
        $stateName = str_replace('State', '', get_class($state));
        echo "\n🔄 [SYSTÈME] Transition de système vers : [ {$stateName} ]\n";

        $this->currentState = $state;
    }

    // --- Délégation des méthodes au State actuel ---

    public function enterArmCode(): void
    {
        $this->currentState->armMode();
    }

    public function enterDisarmCode(): void
    {
        $this->currentState->disarmMode();
    }

    public function motionDetected(): void
    {
        echo "📡 [CAPTEUR] Mouvement détecté dans le salon !\n";
        $this->currentState->triggerSensor();
    }
}

// ============================================
// CONCRETE STATES (Les implémentations spécifiques)
// ============================================

/**
 * ESPÈCE N°1 : Le système est DÉSACTIVÉ.
 */
class DisarmedState implements AlarmState
{
    private SmartAlarmSystem $context;

    public function __construct(SmartAlarmSystem $context)
    {
        $this->context = $context;
    }

    public function armMode(): void
    {
        echo "🟢 [CLAVIER] Code d'armement valide. L'alarme s'active...\n";
        // Transition vers l'état "Armé"
        $this->context->transitionTo(new ArmedState($this->context));
    }

    public function disarmMode(): void
    {
        echo "🟢 [CLAVIER] Le système est DÉJÀ désarmé. Rien à faire.\n";
    }

    public function triggerSensor(): void
    {
        // Le système s'en fout, car il est éteint.
        echo "🟢 [TRAITEMENT] Ignoré. Le système de surveillance est désactivé.\n";
    }
}

/**
 * ESPÈCE N°2 : Le système est ARMÉ ET PRÊT.
 */
class ArmedState implements AlarmState
{
    private SmartAlarmSystem $context;

    public function __construct(SmartAlarmSystem $context)
    {
        $this->context = $context;
    }

    public function armMode(): void
    {
        echo "🔴 [CLAVIER] Le système est DÉJÀ armé.\n";
    }

    public function disarmMode(): void
    {
        echo "🔴 [CLAVIER] Code de désarmement valide.\n";
        // Transition vers l'état "Désarmé"
        $this->context->transitionTo(new DisarmedState($this->context));
    }

    public function triggerSensor(): void
    {
        echo "🔴 [TRAITEMENT] INTRUSION ! Déclenchement de l'alarme immédiat !\n";
        // Urgence ! Le capteur a vu quelque chose alors qu'on était armé, on passe en mode Sirène !
        $this->context->transitionTo(new TriggeredState($this->context));
    }
}

/**
 * ESPÈCE N°3 : Le système est DÉCLENCHÉ (LA SIRÈNE HURLE).
 */
class TriggeredState implements AlarmState
{
    private SmartAlarmSystem $context;

    public function __construct(SmartAlarmSystem $context)
    {
        $this->context = $context;
        $this->startSiren();
    }

    private function startSiren(): void
    {
        echo "🚨 WEE WOO WEE WOO ! LA SIRÈNE HURLE ! ENVOI DE SMS A LA POLICE ! 🚨\n";
    }

    public function armMode(): void
    {
        echo "🚨 [CLAVIER] Action impossible : Vous devez d'abord désarmer la sirène pour réarmer le système !\n";
    }

    public function disarmMode(): void
    {
        echo "🚨 [CLAVIER] Code de désarmement d'urgence accepté. Arrêt de la sirène.\n";
        // Transition de retour au calme
        $this->context->transitionTo(new DisarmedState($this->context));
    }

    public function triggerSensor(): void
    {
        // Si d'autres capteurs voient du mouvement pendant que ça hurle déjà
        echo "🚨 [TRAITEMENT] Nouveau mouvement ignoré, la sirène et la police sont DÉJÀ en cours !\n";
    }
}

// ============================================
// DÉMONSTRATION / CLIENT
// ============================================

echo "========================================\n";
echo "   PATTERN STATE\n";
echo "   Système d'Alarme Domotique\n";
echo "========================================\n";

$alarm = new SmartAlarmSystem(); // Démarre en mode Disarmed

echo "\n--- SCÉNARIO 1 : La journée normale ---\n";
// Le chien passe devant le capteur la journée
$alarm->motionDetected();
// Tom essaye de désarmer quelque chose déjà éteint
$alarm->enterDisarmCode();


echo "\n--- SCÉNARIO 2 : Départ de la maison le soir ---\n";
$alarm->enterArmCode(); // Tom tape son code avant de partir
// Un insecte passe près du capteur
$alarm->motionDetected();


echo "\n--- SCÉNARIO 3 : Le voleur est dans la maison ---\n";
// Le voleur essaie d'armer (!?) pendant que ça sonne
$alarm->enterArmCode();
// Un autre capteur repère le voleur en train de fuir
$alarm->motionDetected();


echo "\n--- SCÉNARIO 4 : Le propriétaire rentre en urgence ---\n";
// Tom arrive et coupe tout
$alarm->enterDisarmCode();

echo "\n✅ Le pattern a évité d'écrire de gros 'if/else' dans la classe 'SmartAlarmSystem'. Chaque état gère proprement ses propres actions possibles !\n";
