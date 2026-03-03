<?php

// ============================================
// SINGLETON : Configuration
// ============================================

/**
 * Gestionnaire de configuration - Singleton
 * Garantit qu'il n'existe qu'une seule instance de configuration
 */
class Configuration
{
    private static ?Configuration $instance = null;
    private array $settings = [];

    /**
     * Constructeur privé - empêche l'instanciation directe
     */
    private function __construct()
    {
        // Chargement de la configuration (simulé)
        $this->settings = [
            'app_name' => 'Mon Application',
            'version' => '1.0.0',
            'database' => 'mysql://localhost:3306/mydb',
            'debug' => true
        ];

        echo "⚙️ Configuration chargée\n\n";
    }

    /**
     * Empêche le clonage de l'instance
     */
    private function __clone()
    {
    }

    /**
     * Méthode statique pour obtenir l'unique instance
     */
    public static function getInstance(): Configuration
    {
        if (self::$instance === null) {
            self::$instance = new Configuration();
        }

        return self::$instance;
    }

    /**
     * Récupérer une valeur de configuration
     */
    public function get(string $key): mixed
    {
        return $this->settings[$key] ?? null;
    }

    /**
     * Définir une valeur de configuration
     */
    public function set(string $key, mixed $value): void
    {
        $this->settings[$key] = $value;
    }

    /**
     * Afficher toute la configuration
     */
    public function display(): void
    {
        echo "📋 Configuration actuelle :\n";
        foreach ($this->settings as $key => $value) {
            $displayValue = is_bool($value) ? ($value ? 'true' : 'false') : $value;
            echo "   {$key}: {$displayValue}\n";
        }
        echo "\n";
    }
}

// ============================================
// DÉMONSTRATION
// ============================================

echo "========================================\n";
echo "   PATTERN SINGLETON\n";
echo "   Gestionnaire de configuration\n";
echo "========================================\n\n";

echo "--- Première récupération de l'instance ---\n";
$config1 = Configuration::getInstance();
$config1->display();

echo "--- Deuxième récupération de l'instance ---\n";
$config2 = Configuration::getInstance();
echo "💡 Aucun nouveau chargement ! (même instance)\n\n";

echo "--- Vérification que c'est la même instance ---\n";
if ($config1 === $config2) {
    echo "✅ Les deux variables pointent vers la MÊME instance\n\n";
}

echo "--- Modification via config1 ---\n";
$config1->set('theme', 'dark');
echo "Ajout de 'theme' = 'dark' via config1\n\n";

echo "--- Lecture via config2 ---\n";
echo "Valeur de 'theme' via config2 : " . $config2->get('theme') . "\n";
echo "✅ La modification est visible partout !\n\n";

echo "--- Configuration finale ---\n";
$config2->display();
