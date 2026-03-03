<?php

// ============================================
// COMPONENT
// ============================================

/**
 * Interface commune pour l'objet de base (le personnage)
 * et tous les décorateurs (l'équipement).
 */
interface Character
{
    public function getName(): string;
    public function getAttack(): int;
    public function getDefense(): int;
    public function getDescription(): string;
}

// ============================================
// CONCRETE COMPONENT
// ============================================

/**
 * L'objet "noyau" de base que l'on va décorer.
 * Ici, un personnage de base sans équipement.
 */
class BaseCharacter implements Character
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAttack(): int
    {
        return 5; // Dégâts de base à mains nues
    }

    public function getDefense(): int
    {
        return 0; // Aucune armure de base
    }

    public function getDescription(): string
    {
        return "Un paysan en haillons";
    }
}

// ============================================
// DECORATOR (Abstrait)
// ============================================

/**
 * Le Décorateur Abstrait implémente la même interface que le Component
 * ET possède une référence vers un Component. 
 * Il "enveloppe" ce component et lui délègue le travail par défaut.
 */
abstract class CharacterEquipmentDecorator implements Character
{
    protected Character $character; // Le personnage "enveloppé"

    public function __construct(Character $character)
    {
        $this->character = $character;
    }

    public function getName(): string
    {
        return $this->character->getName();
    }

    public function getAttack(): int
    {
        return $this->character->getAttack();
    }

    public function getDefense(): int
    {
        return $this->character->getDefense();
    }

    public function getDescription(): string
    {
        return $this->character->getDescription();
    }
}

// ============================================
// CONCRETE DECORATORS
// ============================================

/**
 * Décorateur : Épée Longue
 * Ajoute +15 en attaque.
 */
class LongSword extends CharacterEquipmentDecorator
{
    public function getAttack(): int
    {
        return $this->character->getAttack() + 15; // Délégation + Comportement ajouté
    }

    public function getDescription(): string
    {
        return $this->character->getDescription() . " armé d'une Épée Longue";
    }
}

/**
 * Décorateur : Bouclier Lourd
 * Ajoute +10 en défense.
 */
class HeavyShield extends CharacterEquipmentDecorator
{
    public function getDefense(): int
    {
        return $this->character->getDefense() + 10;
    }

    public function getDescription(): string
    {
        return $this->character->getDescription() . " portant un Bouclier Lourd";
    }
}

/**
 * Décorateur : Anneau Magique de Force
 * Ajoute +50 en attaque et modifie le nom.
 */
class MagicRingOfPower extends CharacterEquipmentDecorator
{
    public function getName(): string
    {
        return "Seigneur " . $this->character->getName(); // Altération du nom
    }

    public function getAttack(): int
    {
        return $this->character->getAttack() + 50;
    }

    public function getDescription(): string
    {
        return $this->character->getDescription() . " dont le doigt brille d'un Anneau Magique";
    }
}

// ============================================
// DÉMONSTRATION
// ============================================

echo "========================================\n";
echo "   PATTERN DECORATOR\n";
echo "   Système d'Équipement (RPG)\n";
echo "========================================\n\n";

/**
 * Fonction helper pour afficher les stats facilement
 */
function printStats(Character $c, string $eventName): void
{
    echo "--- " . strtoupper($eventName) . " ---\n";
    echo "Nom      : " . $c->getName() . "\n";
    echo "Classe   : " . $c->getDescription() . "\n";
    echo "Attaque  : " . $c->getAttack() . "\n";
    echo "Défense  : " . $c->getDefense() . "\n\n";
}

// 1. Création du personnage de base (Component)
$hero = new BaseCharacter("Arthur");
printStats($hero, "Début de l'aventure");

// 2. Arthur trouve une Épée Longue (Décoration 1)
// On enveloppe notre $hero dans le décorateur LongSword
$hero = new LongSword($hero);
printStats($hero, "Trouve une arme");

// 3. Arthur achète un Bouclier (Décoration 2)
// On enveloppe le héros (qui a déjà l'épée) avec le bouclier !
$hero = new HeavyShield($hero);
printStats($hero, "Équipe un bouclier");

// 4. Arthur trouve l'Anneau Magique au fond d'un donjon (Décoration 3)
// L'empilement continue comme des poupées russes.
$hero = new MagicRingOfPower($hero);
printStats($hero, "Découvre un artefact légendaire");

echo "✅ Le pattern Decorator a permis d'ajouter dynamiquement des stats et de l'équipement sans modifier la classe de base, et sans créer une hiérarchie d'héritage !\n";
