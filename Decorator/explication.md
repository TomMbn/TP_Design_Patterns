# Decorator (Décorateur)

## Problème qu'il résout

Le pattern Decorator résout le problème de l'**explosion combinatoire des sous-classes** lorsque l'on souhaite ajouter dynamiquement plusieurs comportements ou responsabilités supplémentaires à un objet.

**Exemple du problème :** Imaginez un jeu vidéo de rôle (RPG). Vous avez une classe `Personnage`. Vous voulez qu'il puisse équiper une épée, un bouclier, ou une armure lourde. Si vous utilisez l'héritage, vous devrez créer `PersonnageAvecEpee`, `PersonnageAvecBouclier`, `PersonnageAvecEpeeEtBouclier`, `PersonnageAvecArmureLourde`, etc. Le nombre de classes pour couvrir toutes les combinaisons possibles devient incontrôlable. De plus, les comportements sont figés à la compilation, on ne peut pas les modifier en cours d'exécution.

## Principe de fonctionnement

Le pattern Decorator **"enveloppe" (wraps)** l'objet original dans un objet Décorateur qui implémente la même interface. Le décorateur délègue le travail à l'objet enveloppé, mais peut exécuter du code supplémentaire **avant ou après** cette délégation.

Puisque le décorateur a la même interface que l'objet qu'il décore, on peut empiler plusieurs décorateurs les uns sur les autres (comme des poupées russes) !

**Analogie :** Pensez à un abonnement à une plateforme de streaming vidéo (VOD). Vous commencez par souscrire au "Forfait Basique" qui vous donne accès au catalogue (votre classe de base). Ensuite, vous trouvez la qualité trop basse et ajoutez l'option "Résolution 4K" (un décorateur) qui s'ajoute à votre forfait. Le mois suivant, pour partager votre compte, vous ajoutez l'option "Écrans Multiples" (un deuxième décorateur) par-dessus. Le prix mensuel et les fonctionnalités se cumulent dynamiquement à chaque couche ajoutée. Vous n'avez pas eu besoin que la plateforme crée un forfait fixe appelé "Basique + 4K + Multi-écrans", vous avez simplement "décoré" votre abonnement de base avec les options désirées à la volée.
## Structure (rôles des classes)

### 1. **Component (Sujet abstrait)**
- L'interface commune (ou classe abstraite) partagée à la fois par l'objet de base (le "noyau") et tous les décorateurs qui vont l'entourer.

### 2. **ConcreteComponent (Sujet concret)**
- L'objet de base initial auquel on veut ajouter des responsabilités (ex: Le Personnage nu sans équipement).

### 3. **Decorator (Décorateur abstrait)**
- Implémente l'interface `Component`.
- Contient une référence (attribut) vers l'objet `Component` qu'il enveloppe (qui peut être un `ConcreteComponent` ou un autre `Decorator`).
- Par défaut, il délègue toutes les méthodes à l'objet enveloppé.

### 4. **ConcreteDecorator (Décorateurs concrets)**
- Héritent du `Decorator` abstrait.
- Ajoutent de nouvelles fonctionnalités ou modifient les valeurs renvoyées (ex: `SwordDecorator` augmente l'attaque retournée par l'objet enveloppé).

## Avantages

✅ **Flexibilité extrême** : Permet d'ajouter ou retirer des responsabilités à un objet dynamiquement à l'exécution (runtime).  
✅ **Alternative à l'héritage** : Évite de créer des dizaines de sous-classes liées statiquement.  
✅ **Multiples additions** : On peut wrapper un objet avec le même décorateur plusieurs fois (ex: ajouter deux fois "+10 Attaque").  
✅ **Single Responsibility Principle (SRP)** : On sépare les comportements en multiples petites classes spécialisées au lieu d'une grosse classe fourre-tout.

## Inconvénients

❌ **Beaucoup de petits objets** : Le code génère de nombreux petits objets juxtaposés ressemblant tous à la même interface, ce qui peut rendre le débogage complexe.  
❌ **Ordre d'instanciation** : Imposer un ordre spécifique d'empilement des décorateurs peut être difficile à maintenir.  
❌ **Instanciation peu élégante** : Créer l'objet complet devient verbeux (`new DecoratorA(new DecoratorB(new ConcreteComponent()))`), d'où l'importance de le coupler au pattern Builder ou Factory !

## Cas d'usage réel possible

### 1. **Traitement de Flux (Streams)**
- C'est l'exemple classique des bibliothèques standards (Java, PHP, C#). Un flux file (`FileInputStream`) est "décoré" par un flux buffer (`BufferedInputStream`), lui-même décoré par un décrypteur, etc.

### 2. **Formatage / Filtre de données**
- Une chaîne de texte de base que l'on passe dans des filtres optionnels : `HtmlEncodeDecorator`, `MarkdownDecorator`, `ProfanityFilterDecorator`.

### 3. **Calcul de Prix et Réductions dans un e-commerce**
- Un panier de base à 100€ "enveloppé" par la réduction "Soldes" (-20%), enveloppé par la "Livraison Express" (+15€).

### 4. **Middlewares / Intercepteurs Web**
- Le système de routage d'un framework (Laravel, Symfony) où la requête brute est "décorée" par le middleware d'Auth, puis par le middleware de Logs, puis par le middleware CORS.

### 5. **Système d'Équipement RPG**
- Comme détaillé en introduction, cumuler les bonus de statistiques sur un avatar à mesure qu'il s'équipe d'objets.
