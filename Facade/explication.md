# Facade (Façade)

## Problème qu'il résout

Le pattern Facade résout le problème de la **complexité d'utilisation d'un système vaste et complexe** (composé de nombreuses classes et sous-systèmes). 

**Exemple du problème :** Imaginons le besoin d'intégrer une bibliothèque complexe pour faire du déploiement d'applications. Pour déployer une simple application, il faut initialiser un gestionnaire Git, récupérer le code, configurer un système de build, compiler le code, configurer un serveur cloud, y envoyer les fichiers, puis redémarrer les services. Cela oblige le code client (le script) à connaître en détail toutes ces classes et étapes, créant un **couplage fort** entre l'application et le fonctionnement interne de cette bibliothèque.

## Principe de fonctionnement

Le pattern Facade **fournit une interface simplifiée (une classe "Façade")** de plus haut niveau pour interagir avec un sous-système complexe. Cette façade délègue intelligemment les requêtes simples du client aux différents composants appropriés du sous-système.

**Analogie :** Prenons l'exemple du bouton "Démarrer" d'une voiture. Un conducteur (le client) n'a qu'à appuyer sur un seul bouton (la façade). Sous le capot, des dizaines de systèmes s'activent dans un ordre précis : la pompe à carburant s'allume, le démarreur tourne, les bougies s'activent, l'alternateur prend le relais. Il n'est pas nécessaire de savoir comment tout cela fonctionne dans le détail, la façade s'en occupe.

## Structure (rôles des classes)

### 1. **Facade (La Façade)**
- Offre un accès pratique et simplifié à une partie des fonctionnalités du sous-système.
- Elle sait exactement à quelles classes du sous-système faire appel et dans quel ordre.

### 2. **Subsystems (Les Sous-systèmes complexes)**
- Un ensemble de classes variées qui effectuent le vrai travail.
- Elles ne connaissent pas l'existence de la façade (elles fonctionnent de manière totalement indépendante).

### 3. **Client**
- Interagit uniquement avec la Façade au lieu d'appeler directement les nombreux objets des sous-systèmes.

## Avantages

✅ **Simplicité** : Réduit considérablement la complexité pour le code client.  
✅ **Découplage** : Isole le code client de la logique complexe et des évolutions internes de la bibliothèque/du sous-système.  
✅ **Point d'entrée unique** : Favorise une meilleure organisation et un point de contrôle d'entrée.

## Inconvénients

❌ **Risque de "God Object"** : Si on ajoute trop de fonctionnalités à la façade, elle peut devenir un "Objet Dieu" omnipotent couplé à toutes les classes de l'application.  
❌ **Perte de flexibilité** : La façade cache intentionnellement certaines configurations avancées. Si un client a besoin d'un réglage très pointu, la façade peut s'avérer insuffisante (le client devra quand même utiliser le sous-système directement).

## Cas d'usage réel possible

### 1. **Système de déploiement DevOps (CI/CD)**
- Une classe `DeployerFacade` qui va orchestrer les classes `Git`, `Docker`, `AwsEC2` et `SlackNotifier` via une simple méthode `deploy("my-app")`.

### 2. **Conversion de fichiers multimédia**
- Une bibliothèque comme FFmpeg est très complexe. Une façade `VideoConverter` peut cacher l'initialisation des codecs, le multiplexage, et l'extraction audio derrière une méthode `convert("video.mp4", "avi")`.

### 3. **Processus d'achat (E-commerce)**
- Une méthode `checkout()` qui englobe la vérification du stock (`Inventory`), le prélèvement bancaire (`PaymentGateway`), l'envoi du reçu (`EmailService`) et la création du colis (`Shipping`).

### 4. **Lanceur de jeux vidéo (Game Engine)**
- Un moteur physique, un moteur de rendu 3D, un gestionnaire d'événements et un gestionnaire de son cachés derrière une classe `Game` avec une méthode `start()`.

### 5. **Connexion à une API tierce complexe**
- Une API nécessitant l'authentification OAuth2, la récupération d'un token, le rafraîchissement du token, puis la requête finale, cachée derrière une simple méthode `getUserData()`.
