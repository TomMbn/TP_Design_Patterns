# Composite (Objet Composite)

## Problème qu'il résout

Le pattern Composite résout le problème du **traitement uniforme d'objets individuels et de compositions d'objets** lorsqu'ils sont structurés sous forme d'arbre (hiérarchie).

**Exemple du problème :** Imaginons le développement d'une application de lecteur de musique. L'utilisateur peut créer des `Chansons` simples, mais aussi des `Playlists`. Et une `Playlist` peut elle-même contenir d'autres sous-playlists ou d'autres chansons. S'il faut calculer la durée totale ou lancer la lecture, il est nécessaire de constamment vérifier le type de l'objet : "Est-ce une chanson ? Alors je joue. Est-ce une playlist ? Alors je dois boucler sur ses éléments. Ah, et si l'élément de la playlist est lui-même une playlist ? Je dois faire une récursion...". Le code devient vite un cauchemar de conditions `if ($item instanceof Playlist)`.

## Principe de fonctionnement

Le pattern Composite force tous les éléments (les éléments simples "Feuilles" et les éléments conteneurs "Nœuds/Composites") à partager une **interface commune**. Grâce à cette interface, le code client peut manipuler n'importe quel élément de l'arbre sans se soucier de savoir s'il s'agit d'un objet simple ou d'un groupe complexe.

**Analogie :** Prenons l'exemple des boîtes de rangement (matriochkas ou cartons de déménagement). Le client veut connaître le poids total d'un grand carton. Ce grand carton (Composite) contient des petits objets simples comme une lampe (Feuille) et des petites boîtes (Composites) qui elles-mêmes contiennent des livres (Feuilles). Plutôt que de dire au transporteur comment vider précisément chaque boîte pour peser chaque objet et tout additionner, chaque boîte et objet a juste l'obligation de répondre à la question "Quel est ton poids ?". La lampe renvoie 2kg. La petite boîte interroge ses livres puis renvoie la somme (5kg). Le grand carton additionne le tout et répond 7kg. Le transporteur ne fait qu'une seule demande uniforme à l'élément de plus haut niveau.

## Structure (rôles des classes)

### 1. **Component (L'Interface Commune)**
- Déclare l'interface (ex: `play()`, `getDuration()`) partagée par tous les composants de l'arbre (les simples et les complexes).

### 2. **Leaf (La Feuille / Élément simple)**
- C'est l'objet terminal de l'arbre qui n'a pas d'enfants (ex: La Chanson).
- C'est lui qui fait le vrai travail final (renvoyer sa propre durée).

### 3. **Composite (Le Nœud / Le Conteneur)**
- Représente un élément complexe possédant des enfants (ex: La Playlist).
- Il implémente les méthodes de l'interface commune de façon à **déléguer l'appel à tous ses enfants**, puis à consolider le résultat (sommer la durée de toutes ses chansons).
- Il possède des méthodes pour gérer ses enfants (`add()`, `remove()`).

### 4. **Client**
- Interagit avec tous les objets à travers l'interface Component.
- Ainsi, le client traite les objets simples et les compositions d'exactement la même manière.

## Avantages

✅ **Simplicité pour le client** : Le client n'a pas besoin de savoir avec quel type d'objet ou quelle complexité d'arbre il interagit (Polymorphisme total).  
✅ **Ajout facile d'éléments** : L'architecture respecte l'OCP (Open/Closed Principle). Il est possible d'ajouter de nouveaux types de feuilles ou de conteneurs très facilement, ils fonctionneront automatiquement avec l'arbre existant.  
✅ **Gestion des structures complexes** : Permet de traiter brillamment des hiérarchies en arbre sans logique de récursion lourde du côté client.

## Inconvénients

❌ **Généralisation excessive** : Si l'on force une interface commune trop large, certains éléments (les Feuilles) auront des méthodes `add()` ou `removeChild()` vides ou levant des exceptions car cela n'a pas de sens pour eux.  
❌ **Typage trop large** : Difficile de restreindre certains conteneurs à n'accepter que *certains types* de composants, puisqu'ils accepteront tout ce qui implémente `Component`.

## Cas d'usage réel possible

### 1. **Systèmes de Fichiers (OS)**
- `Fichier` (Feuille) et `Dossier` (Composite) qui partagent la méthode `.getSize()` ou `.delete()`.

### 2. **Interfaces Graphiques (GUI / DOM)**
- Les balises HTML : un `<div>` (Composite) contient des `<p>` (Feuille) et `<form>` (Composite) qui ont tous la méthode `.render()`.

### 3. **Formulaires et Validation**
- L'appel de `.validate()` sur le formulaire principal (Composite) qui appelle à son tour `.validate()` sur tous les sous-groupes de champs (Composites) ou les inputs de texte finaux (Feuilles).

### 4. **Création de Menus Complexes**
- Des items de menu basiques "Ouvrir" (Feuille) à côté de sous-menus "Fichier" (Composite) qui eux-mêmes contiennent d'autres menus déroulants, réagissant tous à un évènement `.draw()`.

### 5. **Hiérarchies Militaires / Entreprise**
- `Soldat` (Feuille) et `Escouade/Bataillon` (Composite). Un ordre donné au Bataillon (Composite) cascade l'instruction automatiquement à chaque sous-groupe puis à chaque soldat.
