# Builder (Monteur)

## Problème qu'il résout

Le pattern Builder résout le problème du **"Télescopage de constructeurs"** (Telescoping Constructor Anti-pattern) et permet de **créer pas à pas des objets complexes**. 

**Exemple du problème :** Imaginez que vous deviez créer un objet `Ordinateur`. Un ordinateur peut avoir un CPU, de la RAM, un disque dur, mais il peut aussi avoir une carte graphique (ou non), un système de refroidissement liquide (ou non), des LEDs RGB (ou non). Si vous utilisez un seul constructeur, vous allez vous retrouver avec une signature monstrueuse `new Ordinateur(cpu, ram, disque, gpu, refroidissement, rgb, ...)` et devoir passer plein de `null` pour les paramètres optionnels, rendant le code illisible et source d'erreurs.

## Principe de fonctionnement

Le Builder extrait le code de construction de l'objet de sa propre classe et le place dans des objets séparés (les "monteurs"). L'objet est alors construit étape par étape (ex: `setCPU()`, `setRAM()`). 

On peut également utiliser un objet "Directeur" qui connaît l'ordre exact des étapes pour construire des configurations courantes (ex: `construirePCGamer()`).

**Analogie :** Pensez à la commande d'une pizza sur mesure ou d'un sandwich chez Subway. Vous ne dites pas au caissier "Je veux un sandwich avec pain blanc, pas de tomate, salade, poulet, pas de fromage, sauce mayo". C'est trop complexe à retenir d'un coup. Vous passez plutôt par une série d'étapes : choix du pain, puis choix de la viande, puis légumes, etc. Le "Builder" est l'employé qui prépare le sandwich étape par étape selon vos instructions.

## Structure (rôles des classes)

### 1. **Builder (Monteur)**
- Interface qui déclare les étapes de construction communes à tous les types de monteurs (ex: `buildCPU()`, `buildRAM()`).

### 2. **ConcreteBuilder (Monteur Concret)**
- Implémente l'interface Builder et fournit les méthodes spécifiques pour construire les différentes parties de l'objet.
- Contient une instance de l'objet en cours de construction et une méthode pour le récupérer une fois fini (ex: `getResult()`).

### 3. **Product (Produit)**
- L'objet complexe qui est en cours de construction. Contrairement à d'autres patterns de création, les produits du Builder n'ont pas forcément besoin d'avoir une interface commune.

### 4. **Director (Directeur)** - *Optionnel*
- Définit l'ordre dans lequel appeler les étapes de construction.
- Très utile pour créer des configurations prédéfinies et réutilisables.

### 5. **Client**
- Associe le Builder souhaité au Director et lance la construction.

## Avantages

✅ **Contrôle étape par étape** : Permet de construire des objets complexes de manière ordonnée.  
✅ **Code plus lisible** : Évite le "telescoping constructor" avec ses dizaines de paramètres incompréhensibles.  
✅ **Réutilisation de code** : Le même code de construction (dans le Directeur) peut créer différentes représentations (avec différents Builders).  
✅ **Immutabilité** : L'objet retourné peut être rendu totalement immutable (lecture seule) puisqu'il est entièrement initialisé avant d'être livré.

## Inconvénients

❌ **Complexité structurelle** : Nécessite la création de plusieurs nouvelles classes ou interfaces (Builder, ConcreteBuilder, Director, Product).  
❌ **Parfois superflu** : Si l'objet n'est pas si complexe ou a peu de paramètres optionnels, des *setter* simples suffisent.

## Cas d'usage réel possible

### 1. **Génération de requêtes SQL**
- Construction étape par étape : `.select('id')`, `.from('users')`, `.where('age > 18')`, `.orderBy('name')`, puis `.build()`.

### 2. **Construction de documents complexes (HTML, XML, PDF)**
- Ajout progressif : ajouter l'en-tête, ajouter un paragraphe, ajouter une image, puis générer le fichier.

### 3. **Configurations système complexes**
- Monter un PC, configurer une voiture avec des options, assembler un menu complet de restaurant.

### 4. **Tests Unitaires (Test Data Builders)**
- Générer des objets mock/fakes complexes pour les tests avec des données aléatoires ou spécifiques (`UserBuilder().withAdminRole().build()`).

### 5. **Création d'URL (URL Builders)**
- Manipulation sécurisée des protocoles, domaines, chemins et paramètres GET de requêtes HTTP.
