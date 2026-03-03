<?php

// ============================================
// ABSTRACT PRODUCTS
// ============================================

/**
 * Produit Abstrait A : Ampoule connectée
 */
interface SmartBulb
{
    public function turnOn(): void;
    public function setColor(string $color): void;
}

/**
 * Produit Abstrait B : Thermostat connecté
 */
interface SmartThermostat
{
    public function setTemperature(float $temp): void;
    public function displayStatus(): void;
}

// ============================================
// CONCRETE PRODUCTS : Famille Apple (HomeKit)
// ============================================

class AppleBulb implements SmartBulb
{
    public function turnOn(): void
    {
        echo "🍏 [HomeKit] Allumage de l'ampoule Apple...\n";
    }

    public function setColor(string $color): void
    {
        echo "🍏 [HomeKit] Changement de la couleur de l'ampoule Apple en {$color}\n";
    }
}

class AppleThermostat implements SmartThermostat
{
    private float $currentTemp = 20.0;

    public function setTemperature(float $temp): void
    {
        $this->currentTemp = $temp;
        echo "🍏 [HomeKit] Réglage du thermostat Apple sur {$temp}°C\n";
    }

    public function displayStatus(): void
    {
        echo "🍏 [HomeKit] Statut du thermostat Apple : {$this->currentTemp}°C\n";
    }
}

// ============================================
// CONCRETE PRODUCTS : Famille Google (Google Home)
// ============================================

class GoogleBulb implements SmartBulb
{
    public function turnOn(): void
    {
        echo "🤖 [Google Home] Allumage de l'ampoule Google...\n";
    }

    public function setColor(string $color): void
    {
        echo "🤖 [Google Home] Changement de la couleur de l'ampoule Google en {$color}\n";
    }
}

class GoogleThermostat implements SmartThermostat
{
    private float $currentTemp = 20.0;

    public function setTemperature(float $temp): void
    {
        $this->currentTemp = $temp;
        echo "🤖 [Google Home] Réglage du thermostat Google sur {$temp}°C\n";
    }

    public function displayStatus(): void
    {
        echo "🤖 [Google Home] Statut du thermostat Google : {$this->currentTemp}°C\n";
    }
}

// ============================================
// ABSTRACT FACTORY
// ============================================

/**
 * L'interface Abstract Factory déclare un ensemble de méthodes
 * pour créer les différents produits abstraits.
 */
interface SmartHomeFactory
{
    public function createBulb(): SmartBulb;
    public function createThermostat(): SmartThermostat;
}

// ============================================
// CONCRETE FACTORIES
// ============================================

/**
 * Fabrique Concrète pour l'écosystème Apple
 */
class AppleHomeFactory implements SmartHomeFactory
{
    public function createBulb(): SmartBulb
    {
        return new AppleBulb();
    }

    public function createThermostat(): SmartThermostat
    {
        return new AppleThermostat();
    }
}

/**
 * Fabrique Concrète pour l'écosystème Google
 */
class GoogleHomeFactory implements SmartHomeFactory
{
    public function createBulb(): SmartBulb
    {
        return new GoogleBulb();
    }

    public function createThermostat(): SmartThermostat
    {
        return new GoogleThermostat();
    }
}

// ============================================
// CLIENT
// ============================================

/**
 * Le code client travaille uniquement avec des interfaces.
 * Il ne sait (et ne se soucie) pas de quel écosystème exact il utilise.
 */
class SmartHomeApp
{
    private SmartBulb $bulb;
    private SmartThermostat $thermostat;

    /**
     * L'application reçoit une fabrique complète, garantissant que
     * tous les objets créés seront compatibles (même pile technologique).
     */
    public function __construct(SmartHomeFactory $factory)
    {
        $this->bulb = $factory->createBulb();
        $this->thermostat = $factory->createThermostat();
    }

    public function startMorningRoutine(): void
    {
        echo "\n🌅 --- Lancement de la routine du matin ---\n";
        $this->thermostat->setTemperature(22.5);
        $this->bulb->turnOn();
        $this->bulb->setColor("Blanc chaud");
        $this->thermostat->displayStatus();
    }
}

// ============================================
// DÉMONSTRATION
// ============================================

echo "========================================\n";
echo "   PATTERN ABSTRACT FACTORY\n";
echo "   Écosystème Maison Connectée\n";
echo "========================================\n";

// 1. L'utilisateur a une maison équipée 100% Apple
echo "\n>>> Scénario 1 : Configuration Apple HomeKit\n";
$appleFactory = new AppleHomeFactory();
$app1 = new SmartHomeApp($appleFactory);
$app1->startMorningRoutine();

// 2. L'utilisateur a une maison équipée 100% Google
echo "\n>>> Scénario 2 : Configuration Google Home\n";
$googleFactory = new GoogleHomeFactory();
$app2 = new SmartHomeApp($googleFactory);
$app2->startMorningRoutine();

echo "\n✅ L'application cliente utilise des écosystèmes complets sans dépendre des classes concrètes !\n";
