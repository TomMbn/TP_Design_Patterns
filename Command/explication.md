# Command (Commande)

## Problème qu'il résout

Le pattern Command résout le problème du **couplage fort entre l'expéditeur d'une requête et son destinataire** (celui qui l'exécute réellement). 

**Exemple du problème :** Imaginons le développement du logiciel interne d'une banque. L'interface (UI) possède des boutons pour faire des dépôts, des retraits ou des virements. Si la logique de transaction est codée *directement* dans l'événement "clic" du bouton, l'interface utilisateur devient fortement couplée à la logique métier de la banque. Pire encore : s'il faut annuler (Undo) une opération, ou planifier un virement dans le temps, aucun moyen ne permet de sauvegarder la requête "Virement" sous forme d'objet pour la traiter plus tard.

## Principe de fonctionnement

Le pattern Command transforme la requête elle-même en un **objet encapsulé**. Cet objet contient toutes les informations nécessaires (les paramètres de la méthode, l'objet ciblé) pour exécuter l'action plus tard.

**Analogie :** Prenons l'exemple d'un client dans un grand restaurant étoilé. Le client (l'expéditeur) ne va pas dans les cuisines crier au cuisinier (le destinataire) la recette qu'il veut manger. À la place, il donne sa commande au serveur. Le serveur écrit cette commande sur un **bout de papier (l'objet Commande)** et le dépose en cuisine. Ce bon de commande contient toutes les infos ("Table 4, Cuisson saignante") et la cuisine peut le traiter quand elle est prête, ou même l'annuler avant qu'il ne soit préparé.

## Structure (rôles des classes)

### 1. **Command (Interface Commande)**
- Interface déclarant généralement une méthode d'exécution (ex: `execute()`) et souvent une méthode d'annulation (`undo()`).

### 2. **ConcreteCommand (Commande Concrète)**
- Implémente l'interface `Command`. 
- Représente une action spécifique (ex: `DepositCommand`, `WithdrawCommand`).
- Contient une référence vers le **Receiver** et les arguments nécessaires pour lui passer l'ordre.

### 3. **Receiver (Le Destinataire/Récepteur)**
- L'objet métier qui contient la véritable logique (ex: Le CompteBancaire avec sa méthode pour créditer de l'argent).

### 4. **Invoker (L'Invocateur / Expéditeur)**
- La classe qui demande à la commande de s'exécuter.
- Il peut stocker un historique des commandes pour faire du "Undo/Redo".
- Il ne sait rien du Receiver, il sait juste appeler `execute()` sur une Commande.

### 5. **Client**
- C'est lui qui crée le Receiver, instancie les ConcreteCommands avec ce Receiver, et les passe à l'Invoker.

## Avantages

✅ **Fonctionnalités Undo/Redo** : Puisqu'une commande est un objet stocké, on peut faire un historique de ces objets et appeler leurs méthodes `undo()`.  
✅ **Planification & Files d'attente (Queuing)** : On peut stocker les commandes dans une file (RabbitMQ, base de données) pour les exécuter asynchroneusement plus tard.  
✅ **Découplage strict** : Sépare totalement l'interface utilisateur (qui invoque l'action) de la logique métier (qui l'exécute).  
✅ **Macros de commandes** : Permet d'assembler plusieurs commandes dans une "MacroCommande" (Pattern Composite lié).

## Inconvénients

❌ **Surcharge de classes** : Beaucoup de petites classes ajoutées, car il faut créer une nouvelle "ConcreteCommand" pour chaque type d'action possible dans le système.  
❌ **Complexité de conception** : Introduit plusieurs couches (Invoker, Command, Receiver) qui peuvent paraître lourdes pour des actions simples qui ne nécessitent pas de système d'Undo.

## Cas d'usage réel possible

### 1. **Transactions Bancaires (Undo/Redo)**
- Conserver une trace de tous les dépôts/retraits sous forme d'objets pour pouvoir annuler la dernière transaction si nécessaire.

### 2. **Éditeurs de Texte et Logiciels Graphiques (Photoshop)**
- L'historique d'actions : chaque coup de pinceau, chaque texte tapé (Ctrl+Z / Ctrl+Y).

### 3. **Systèmes de files d'attente (Job Queues/Workers)**
- Convertir des requêtes web (ex: "Générer un PDF", "Envoyer Email") en objets "Jobs" stockés en BDD pour être traités par un worker (cron) la nuit.

### 4. **Boutons et Raccourcis Clavier d'un Jeu Vidéo**
- Associer la touche d'une manette (Invoker) non pas à une action statique ("Sauter"), mais à une interface Commande. Ainsi on peut facilement remapper les touches dans le menu des options en changeant la Commande associée à la touche.

### 5. **Assistants Vocaux (Domotique)**
- "Alexa, lance l'ambiance Soirée" (Invocateur) : Envoie une `MacroCommand` qui boucle sur d'autres commandes (`TurnOnLightCommand`, `UnlockDoorCommand`, `PlayMusicCommand`).
