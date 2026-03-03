<?php

// ============================================
// SOUS-SYSTÈMES COMPLEXES
// ============================================
// Ces classes représentent une machinerie complexe.
// Utilisées séparément, elles nécessitent beaucoup de configuration.

class GitRepository
{
    public function clone(string $url): void
    {
        echo "📂 [GIT] Clonage du dépôt depuis {$url}...\n";
    }

    public function checkout(string $branch): void
    {
        echo "📂 [GIT] Changement sur la branche '{$branch}'...\n";
    }
}

class BuildSystem
{
    public function installDependencies(): void
    {
        echo "⚙️  [BUILD] Installation des dépendances (npm/composer)...\n";
    }

    public function compile(): void
    {
        echo "⚙️  [BUILD] Compilation des assets et du code source...\n";
    }

    public function runTests(): bool
    {
        echo "🧪 [TEST] Exécution des tests unitaires... (Succès)\n";
        return true; // Simule un succès
    }
}

class CloudServer
{
    public function provision(string $size): void
    {
        echo "☁️  [CLOUD] Provisionnement d'un serveur de taille '{$size}'...\n";
    }

    public function uploadFiles(): void
    {
        echo "☁️  [CLOUD] Transfert sécurisé des fichiers vers le serveur...\n";
    }

    public function restartServices(): void
    {
        echo "🔄 [SERVER] Redémarrage des services web (Nginx/PHP-FPM)...\n";
    }
}

class SlackNotifier
{
    public function sendMessage(string $channel, string $message): void
    {
        echo "💬 [SLACK] Message envoyé sur #{$channel} : {$message}\n";
    }
}

// ============================================
// FACADE
// ============================================

/**
 * La classe Facade fournit une interface simple pour orchestrer
 * de manière logique tous les sous-systèmes complexes.
 */
class DeploymentFacade
{
    private GitRepository $git;
    private BuildSystem $build;
    private CloudServer $cloud;
    private SlackNotifier $notifier;

    /**
     * La façade gère la création/injection des sous-systèmes
     */
    public function __construct()
    {
        $this->git = new GitRepository();
        $this->build = new BuildSystem();
        $this->cloud = new CloudServer();
        $this->notifier = new SlackNotifier();
    }

    /**
     * L'opération principale (simple vue de l'extérieur)
     */
    public function deployAutomated(string $repoUrl, string $branch, string $env): void
    {
        echo "🚀 DÉMARRAGE DU DÉPLOIEMENT vers [{$env}]\n";
        echo "--------------------------------------------------------\n";
        
        $this->notifier->sendMessage("devops", "Déploiement initié sur l'environnement {$env}...");

        // 1. Récupération du code
        $this->git->clone($repoUrl);
        $this->git->checkout($branch);

        // 2. Build et Tests
        $this->build->installDependencies();
        $this->build->compile();
        
        if (!$this->build->runTests()) {
            $this->notifier->sendMessage("devops", "❌ Échec du déploiement : les tests ont échoué.");
            throw new Exception("Pipeline arrêté à cause des tests.");
        }

        // 3. Déploiement Cloud
        $size = ($env === 'production') ? 'large' : 'small';
        $this->cloud->provision($size);
        $this->cloud->uploadFiles();
        $this->cloud->restartServices();

        // 4. Notification finale
        $this->notifier->sendMessage("devops", "✅ Déploiement terminé avec succès sur l'environnement {$env} !");
        
        echo "--------------------------------------------------------\n\n";
    }
}

// ============================================
// CLIENT (L'utilisateur final / Le script)
// ============================================

echo "========================================\n";
echo "   PATTERN FACADE\n";
echo "   Système de Déploiement DevOps (CI/CD)\n";
echo "========================================\n\n";

// SANS FACADE :
// Le développeur devrait instancier GitRepository, BuildSystem, CloudServer, SlackNotifier...
// Et se souvenir de l'ordre exact de la quinzaine de méthodes à appeler pour déployer.

// AVEC FACADE :
// Le développeur n'a besoin d'interagir qu'avec une seule classe et une seule méthode très claire.

$deployer = new DeploymentFacade();

// Déploiement en pré-production
$deployer->deployAutomated(
    "https://github.com/mon-entreprise/mon-api.git", 
    "develop", 
    "staging"
);

// Déploiement en production
$deployer->deployAutomated(
    "https://github.com/mon-entreprise/mon-api.git", 
    "main", 
    "production"
);
