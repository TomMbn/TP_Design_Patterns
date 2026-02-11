<?php

// ============================================
// INTERFACE TARGET : Logger
// ============================================

/**
 * Interface que notre application utilise pour logger
 */
interface Logger
{
    public function log(string $level, string $message): void;
}

// ============================================
// ADAPTEE : Ancien système de logs
// ============================================

/**
 * Ancien système de logs avec une interface différente
 * On ne peut pas le modifier (code legacy ou bibliothèque externe)
 */
class LegacyFileLogger
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * Méthode de l'ancien système - interface incompatible
     */
    public function writeLog(string $text, int $severity): void
    {
        $severityLabel = match ($severity) {
            1 => "INFO",
            2 => "WARNING",
            3 => "ERROR",
            default => "UNKNOWN"
        };

        echo "[LEGACY] {$severityLabel} - {$text} (fichier: {$this->filename})\n";
    }
}

// ============================================
// ADAPTER : Adaptateur pour LegacyFileLogger
// ============================================

/**
 * L'adaptateur traduit l'interface Logger vers LegacyFileLogger
 */
class LegacyLoggerAdapter implements Logger
{
    private LegacyFileLogger $legacyLogger;

    public function __construct(LegacyFileLogger $legacyLogger)
    {
        $this->legacyLogger = $legacyLogger;
    }

    /**
     * Implémente l'interface Logger en traduisant vers l'ancien système
     */
    public function log(string $level, string $message): void
    {
        // Traduction du niveau de log vers le format legacy
        $severity = match (strtoupper($level)) {
            "INFO" => 1,
            "WARNING" => 2,
            "ERROR" => 3,
            default => 1
        };

        // Appel à l'ancien système avec les paramètres traduits
        $this->legacyLogger->writeLog($message, $severity);
    }
}

// ============================================
// CONCRETE IMPLEMENTATION : Logger moderne
// ============================================

/**
 * Implémentation moderne de Logger (pour comparaison)
 */
class ModernLogger implements Logger
{
    public function log(string $level, string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        echo "[MODERN] {$timestamp} [{$level}] {$message}\n";
    }
}

// ============================================
// CLIENT : Application
// ============================================

/**
 * L'application utilise l'interface Logger
 * Elle ne sait pas si c'est un logger moderne ou adapté
 */
class Application
{
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function run(): void
    {
        $this->logger->log("INFO", "Application démarrée");
        $this->logger->log("WARNING", "Mémoire faible");
        $this->logger->log("ERROR", "Connexion base de données échouée");
    }
}

// ============================================
// DÉMONSTRATION
// ============================================

echo "========================================\n";
echo "   PATTERN ADAPTER - Système de logs\n";
echo "========================================\n\n";

echo "--- Utilisation du logger moderne ---\n";
$modernLogger = new ModernLogger();
$app1 = new Application($modernLogger);
$app1->run();

echo "\n--- Utilisation du logger legacy adapté ---\n";
$legacyLogger = new LegacyFileLogger("app.log");
$adaptedLogger = new LegacyLoggerAdapter($legacyLogger);
$app2 = new Application($adaptedLogger);
$app2->run();

