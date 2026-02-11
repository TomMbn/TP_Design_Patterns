# Factory Method (Méthode Fabrique)

## Problème qu'il résout

Le pattern Factory Method résout le problème de la **création d'objets sans spécifier leur classe concrète**. Il permet de déléguer la logique d'instanciation aux sous-classes, évitant ainsi un couplage fort entre le code client et les classes concrètes.

**Exemple du problème :** Imaginez une application de notifications qui doit envoyer des messages par Email, SMS ou Push. Sans Factory Method, le code client devrait connaître toutes les classes concrètes (`new EmailNotification()`, `new SmsNotification()`, etc.) et utiliser des conditionnelles pour choisir laquelle instancier, créant un couplage fort et violant le principe Ouvert/Fermé.

## Principe de fonctionnement

Le pattern Factory Method définit une **interface pour créer un objet**, mais laisse les **sous-classes décider quelle classe instancier**. Il permet à une classe de déléguer l'instanciation à ses sous-classes.

**Analogie :** C'est comme une chaîne de restaurants franchisés. Le siège social (classe abstraite) définit comment préparer un menu, mais chaque restaurant local (sous-classe concrète) décide quels ingrédients locaux utiliser. Le processus est le même, mais les produits finaux peuvent varier.

Le pattern repose sur :
- Une **méthode abstraite** (factory method) dans la classe de base
- Des **implémentations concrètes** de cette méthode dans les sous-classes
- Le **code client** qui utilise la factory method sans connaître la classe concrète créée

## Structure (rôles des classes)

### 1. **Product (Interface produit)**
- Définit l'interface des objets que la factory method crée
- Tous les produits concrets implémentent cette interface

### 2. **ConcreteProduct (Produit concret)**
- Implémente l'interface Product
- Représente les différentes variantes d'objets à créer

### 3. **Creator (Créateur abstrait)**
- Déclare la factory method qui retourne un objet Product
- Peut contenir une implémentation par défaut
- Utilise le produit créé sans connaître sa classe concrète

### 4. **ConcreteCreator (Créateur concret)**
- Implémente la factory method pour créer un ConcreteProduct spécifique
- Chaque sous-classe décide quelle classe de produit instancier

## Avantages

✅ **Principe Ouvert/Fermé** : On peut ajouter de nouveaux types de produits sans modifier le code existant  
✅ **Découplage** : Le code client ne dépend pas des classes concrètes  
✅ **Single Responsibility** : La logique de création est isolée dans un endroit spécifique  
✅ **Flexibilité** : Facile d'ajouter de nouvelles variantes de produits  
✅ **Testabilité** : On peut facilement créer des mocks pour les tests

## Inconvénients

❌ **Complexité accrue** : Nécessite de créer une hiérarchie de classes (Creator + Product)  
❌ **Multiplication des classes** : Chaque nouveau produit nécessite une nouvelle sous-classe Creator  
❌ **Over-engineering** : Peut être trop lourd pour des cas simples  
❌ **Indirection** : Le flux d'exécution est moins direct et peut être difficile à suivre  
❌ **Rigidité** : Si la factory method est mal conçue, elle peut être difficile à étendre

## Cas d'usage réel possible

### 1. **Systèmes de notifications multi-canaux**
- Créer différents types de notifications (Email, SMS, Push, Slack)
- Chaque service de notification crée son type spécifique

### 2. **Génération de documents**
- Créer des documents dans différents formats (PDF, Word, Excel)
- Chaque générateur produit son format spécifique

### 3. **Connexions à différentes bases de données**
- Créer des connexions MySQL, PostgreSQL, MongoDB
- Chaque factory crée le bon type de connexion

### 4. **Parsers de fichiers**
- Créer des parsers pour JSON, XML, CSV, YAML
- Sélection automatique selon l'extension du fichier

### 5. **Interfaces utilisateur multi-plateformes**
- Créer des boutons, fenêtres pour Windows, macOS, Linux
- Chaque factory crée les composants natifs de la plateforme

### 6. **Systèmes de logging**
- Créer différents loggers (fichier, console, base de données, cloud)
- Chaque factory configure et crée son type de logger

### 7. **Création de personnages dans un jeu**
- Créer différentes classes de personnages (Guerrier, Mage, Archer)
- Chaque factory initialise le personnage avec ses attributs spécifiques
