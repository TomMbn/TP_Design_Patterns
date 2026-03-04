# State (État)

## Problème qu'il résout

Le pattern State résout le problème de la **gestion des comportements d'un objet qui changent drastiquement en fonction de son état interne**, et remplace les gigantesques blocs de `switch` ou de `if/else` qui rendent le code monolithique.

**Exemple du problème :** Imaginons le code d'une alarme de sécurité dans une maison. L'alarme a trois états : Désactivée, Activée, et Déclenchée (Sirène). Si l'on conçoit une classe `AlarmSystem` avec une méthode `triggerSensor()` (un capteur repère du mouvement), le comportement de cette méthode va dépendre de l'état. Si l'alarme est désactivée, il ne se passe rien. Si elle est activée, elle doit hurler. Si elle est déjà en train de hurler, la déclencher à nouveau ne fait rien de plus. Le développeur va alors écrire un grand `switch ($this->state)` dans toutes les méthodes de la classe (`arm()`, `disarm()`, `trigger()`). À chaque nouvel état, il faut modifier toutes les méthodes !

## Principe de fonctionnement

Le pattern State suit le principe suivant : au lieu d'avoir un paramètre `$status` et plein de conditions, **chaque État devient une classe distincte** qui implémente une interface commune. Le Contexte (l'objet principal, comme notre Alarme) possède une référence vers un objet d'État, et il lui **délègue** tout le travail lorsqu'une méthode est appelée.

Pour changer d'état, on dit simplement au Contexte de remplacer son objet d'état actuel par un autre (ex: de passer de `new ArmedState()` à `new TriggeredState()`).

**Analogie :** Prenons l'exemple de l'humeur d'une personne dans la vie de tous les jours (qui agit comme un "état"). Si l'on demande à un ami "Est-ce que je peux t'emprunter 100€ ?", sa réponse va drastiquement changer en fonction de son humeur actuelle (son État interne). S'il est dans l'état "Joyeux", il va les prêter volontiers. S'il est dans l'état "Fâché", il va refuser la demande poliment. S'il est dans l'état "Endormi", il ne va même pas répondre. Cet ami n'a pas un cerveau avec des `if/else`, son comportement découle naturellement de l'état dans lequel il se trouve.

## Structure (rôles des classes)

### 1. **Context (Le Contexte)**
- L'objet principal avec lequel le client interagit (ex: `AlarmSystem`).
- Il conserve une référence vers son état courant (une instance de `State`).
- Il expose souvent une méthode `setState()` permettant aux états de déclencher des transitions.

### 2. **State (L'Interface État)**
- Déclare les méthodes spécifiques à l'état que tous les états concrets doivent fournir (les actions possibles, ex: `arm()`, `disarm()`, `trigger()`).

### 3. **ConcreteState (Les États Concrets)**
- Implémentent l'interface `State` (ex: `DisarmedState`, `ArmedState`).
- Contiennent le comportement spécifique à cet état.
- Ils possèdent généralement une référence permettant de **piloter** le Contexte (pour lui dire de changer d'état vers un nouveau `ConcreteState` à la fin d'une action réussie).

## Avantages

✅ **Single Responsibility Principle (SRP)** : Le code gérant un statut spécifique est regroupé dans une seule et unique classe.  
✅ **Open/Closed Principle (OCP)** : Ajouter de nouveaux états est très facile. Il suffit de créer une nouvelle classe sans toucher au vieux code rempli de `switch` !  
✅ **Suppression des conditions** : Les grands blocs conditionnels liés à l'état de l'objet disparaissent de l'objet Contexte qui devient très léger.

## Inconvénients

❌ **Peut être "Overkill"** : Si l'objet n'a que deux ou trois petits états fixes qui évoluent peu, le pattern rajoute pas mal de classes inutiles et complique le projet.  
❌ **Logique de transition décentralisée** : Si les transitions sont gérées par les *États Concrets* directement, les classes deviennent couplées entre elles, et lire le flux complet des états du début à la fin peut demander de fouiller dans beaucoup de fichiers.

## Cas d'usage réel possible

### 1. **Panier d'achat E-commerce (Commande)**
- Une commande passe par : `Nouveau`, `Payé`, `Expédié`, `Livré`. On ne peut pas "Expédier" une commande si son état n'est pas "Payé". Chaque appel de méthode sur la commande interroge l'état actuel.

### 2. **Machines de Jeu / Automates Fonctionnels (Vending Machines)**
- Distributeur de boissons : `Attente_Piece`, `A_Du_Credit`, `Boisson_Servie`, `Hors_Service`. Insérer une pièce ne réagit pas de la même façon selon le stade.

### 3. **Processus d'approbation documentaire**
- Un article de blog passe par `Brouillon` -> `En_Relecture` -> `Publié`. Un brouillon ne peut pas être commenté par les lecteurs, un publié oui.

### 4. **Systèmes de Sécurité et Alarmes**
- L'exemple du système qui évolue entre état désarmé, armé de nuit, armé total, et en train de sonner.

### 5. **Connexion Audio/Réseau (Sockets)**
- C'est l'exemple historique du GoF. Une connexion TCP possède un état `Listening`, `Connected` ou `Closed`. Le fait d'envoyer un paquet n'aura pas les mêmes effets en fonction du stade.
