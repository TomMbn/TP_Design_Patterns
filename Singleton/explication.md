# Singleton

## Problème qu'il résout

Le pattern Singleton résout le problème de **garantir qu'une classe n'a qu'une seule instance** dans toute l'application et fournit un **point d'accès global** à cette instance.

**Exemple du problème :** Imaginez une application qui gère une connexion à une base de données. Si chaque partie du code crée sa propre connexion, vous vous retrouvez avec des dizaines de connexions ouvertes, gaspillant des ressources. Le Singleton garantit qu'il n'existe qu'une seule connexion partagée par toute l'application.

## Principe de fonctionnement

Le pattern Singleton **empêche la création de multiples instances** en rendant le constructeur privé et en fournissant une méthode statique qui retourne toujours la même instance.

**Analogie :** C'est comme un gouvernement d'un pays. Il ne peut y avoir qu'un seul gouvernement officiel à la fois. Peu importe combien de fois vous demandez "qui gouverne ?", vous obtenez toujours la même réponse : le gouvernement actuel unique.

Le pattern repose sur :
- Un **constructeur privé** (impossible de faire `new MaClasse()`)
- Une **variable statique** qui stocke l'unique instance
- Une **méthode statique** `getInstance()` qui crée ou retourne l'instance

## Structure (rôles des classes)

### 1. **Singleton**
- Constructeur privé pour empêcher l'instanciation directe
- Variable statique privée pour stocker l'unique instance
- Méthode statique publique `getInstance()` pour accéder à l'instance
- Empêche le clonage (méthode `__clone()` privée)

## Avantages

✅ **Instance unique garantie** : Impossible de créer plusieurs instances accidentellement  
✅ **Accès global** : L'instance est accessible partout dans l'application  
✅ **Initialisation paresseuse** : L'instance n'est créée que quand elle est nécessaire  
✅ **Économie de ressources** : Évite la création d'objets coûteux multiples  
✅ **État partagé** : Permet de partager des données entre différentes parties du code

## Inconvénients

❌ **Violation du SRP** : La classe gère à la fois sa logique métier ET son instanciation unique  
❌ **Couplage fort** : Crée une dépendance globale difficile à tester  
❌ **Difficile à tester** : Complique les tests unitaires (état partagé entre tests)  
❌ **Problèmes de concurrence** : Nécessite une gestion spéciale en multi-threading  
❌ **Anti-pattern selon certains** : Considéré comme une variable globale déguisée  
❌ **Difficile à étendre** : Héritage complexe avec un Singleton

## Cas d'usage réel possible

### 1. **Connexion à une base de données**
- Une seule connexion partagée par toute l'application
- Évite d'ouvrir des dizaines de connexions

### 2. **Gestionnaire de configuration**
- Charger la configuration une seule fois
- Accès global aux paramètres de l'application

### 3. **Logger / Système de logs**
- Un seul fichier de log ouvert
- Évite les conflits d'écriture

### 4. **Cache**
- Une seule instance de cache en mémoire
- Partage des données mises en cache

### 5. **Gestionnaire de ressources**
- Pool de connexions
- Gestionnaire de threads
- File d'attente de tâches

### 6. **Registre d'objets**
- Stockage centralisé d'objets partagés
- Service locator pattern

### 7. **Gestionnaire de fenêtres (GUI)**
- Une seule fenêtre principale dans une application desktop
- Gestionnaire d'affichage unique
