# Abstract Factory (Fabrique Abstraite)

## Problème qu'il résout

Le pattern Abstract Factory résout le problème de la **création de familles d'objets liés ou dépendants** sans avoir à spécifier leurs classes concrètes. Il permet de s'assurer que les objets créés ensemble sont compatibles entre eux.

**Exemple du problème :** Imaginons le développement d'un système pour des maisons connectées (Smart Home). Il existe différents types de périphériques (ampoules, thermostats) qui peuvent appartenir à différents écosystèmes (Apple HomeKit, Google Home). Si l'on mélange une ampoule Apple avec un thermostat Google, ils risquent de ne pas communiquer correctement avec le même hub central. Le code client a besoin d'un moyen de créer des composants d'un même écosystème sans avoir à connaître les détails de chaque classe concrète.

## Principe de fonctionnement

L'Abstract Factory fournit une interface pour créer des familles d'objets apparentés ou dépendants. Pour chaque famille d'objets (ex: écosystème Apple), une fabrique concrète est implémentée pour instancier les produits de cette famille.

**Analogie :** Prenons l'exemple de l'achat d'un menu dans une chaîne de restauration rapide. En allant chez McDonald's (la fabrique), ils ne donnent pas un Big Mac avec des frites Burger King et une boisson KFC pour accompagner. Quand on commande un "Menu Burger", la fabrique McDonald's crée une famille de produits spécifiques à sa marque (Burger McDo, Frites McDo, Boisson McDo) qui vont tous bien ensemble. Si l'on change de fabrique (en allant chez KFC), on obtiendra une autre famille de produits avec la même structure de menu (Burger Poulet, Frites KFC, Boisson Pepsi), mais tous cohérents avec l'écosystème de cette nouvelle chaîne.

Le pattern repose sur :
- Des **interfaces pour les sous-produits** (ex: Ampoule, Thermostat)
- Une **interface de Fabrique Abstraite** déclarant des méthodes de création pour chaque sous-produit (`creerAmpoule()`, `creerThermostat()`)
- Des **Fabriques Concrètes** qui implémentent ces méthodes pour une famille donnée

## Structure (rôles des classes)

### 1. **AbstractFactory (Fabrique Abstraite)**
- Déclare les méthodes de création (factory methods) pour chacun des produits abstraits d'une famille.

### 2. **ConcreteFactory (Fabrique Concrète)**
- Implémente les méthodes de création de la fabrique abstraite.
- S'assure de toujours retourner des produits compatibles appartenant à la même famille (ex: GoogleHomeFactory ne crée que des objets Google).

### 3. **AbstractProduct (Produit Abstrait)**
- Déclare l'interface pour un type d'objet produit (ex: Ampoule).

### 4. **ConcreteProduct (Produit Concret)**
- Implémente l'interface du produit abstrait (ex: AmpouleApple, AmpouleGoogle).
- Créé par la fabrique concrète correspondante.

### 5. **Client**
- Utilise uniquement les interfaces (AbstractFactory et AbstractProducts).
- Ne se soucie pas de savoir *quelle* fabrique ou *quels* produits concrets il manipule, tant qu'il reçoit un écosystème cohérent.

## Avantages

✅ **Compatibilité garantie** : Assure que tous les produits créés par une fabrique fonctionneront bien ensemble.  
✅ **Découplage** : Le code client manipule des interfaces, pas des classes concrètes (respect de l'inversion de dépendance).  
✅ **Single Responsibility Principle (SRP)** : La logique de création est centralisée dans les fabriques.  
✅ **Open/Closed Principle (OCP)** : Il est possible d'ajouter de nouvelles familles de produits (ex: ajouter Amazon Alexa) sans casser le code existant.

## Inconvénients

❌ **Complexité structurelle** : Introduit beaucoup de nouvelles interfaces et de classes dans le projet.  
❌ **Difficulté d'ajouter de nouveaux *types* de produits** : S'il faut ajouter une `Camera` à toutes les familles, il est nécessaire de modifier l'interface `AbstractFactory` et **toutes** les fabriques concrètes existantes.

## Cas d'usage réel possible

### 1. **Systèmes d'interface utilisateur (UI / GUI)**
- Créer des composants graphiques (Boutons, Cases à cocher, Fenêtres) harmonisés pour différents OS (Windows, macOS, Linux) ou Thèmes (Dark Mode, Light Mode).

### 2. **Écosystèmes IoT / Maison connectée**
- Créer des périphériques (Capteurs de température, Ampoules, Serrures) spécifiques à un protocole ou fournisseur (Apple HomeKit, Google Home, Zigbee).

### 3. **Connexion aux Bases de Données (ORM)**
- Obtenir un ensemble d'objets compatibles avec une base spécifique (Connection, Command, DataReader) que l'on soit sous MySQL ou PostgreSQL.

### 4. **Moteur de rendu 3D / Jeux vidéo**
- Générer des ressources graphiques, des shaders et des modèles de collisions adaptés soit à DirectX (Windows), soit à OpenGL/Vulkan, soit à Metal (Apple) à partir de la même base de code.

### 5. **Génération de documents structurés**
- Produire une structure d'en-tête, de corps et de pied de page soit pour un export PDF, soit pour un export Word, avec un style cohérent.
