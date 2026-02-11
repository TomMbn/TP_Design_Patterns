# Strategy (Stratégie)

## Problème qu'il résout

Le pattern Strategy résout le problème de la **sélection d'algorithmes à l'exécution**. Sans ce pattern, on se retrouve souvent avec des structures conditionnelles complexes (if/else ou switch) qui rendent le code difficile à maintenir et à étendre.

**Exemple du problème :** Imaginez un système de compression de fichiers qui doit gérer plusieurs algorithmes (ZIP, GZIP, RAR, compression rapide). Sans Strategy, vous auriez un énorme bloc if/else dans votre code, et ajouter un nouvel algorithme nécessiterait de modifier le code existant, violant le principe Ouvert/Fermé.

## Principe de fonctionnement

Le pattern Strategy permet de **définir une famille d'algorithmes**, de les **encapsuler** dans des classes séparées, et de les rendre **interchangeables**. Le client peut choisir quel algorithme utiliser sans connaître les détails de son implémentation.

**Analogie :** C'est comme choisir un algorithme de compression pour archiver des fichiers. Vous pouvez utiliser ZIP (équilibré), GZIP (optimisé web), ou compression rapide (vitesse). Chaque algorithme a sa propre logique (taux de compression, vitesse, compatibilité), mais l'objectif reste le même : réduire la taille du fichier. Vous choisissez la stratégie selon le contexte (type de fichier, priorité vitesse/taille, usage final).

Le pattern se base sur la **composition plutôt que l'héritage** : au lieu de créer des sous-classes pour chaque variante, on injecte le comportement souhaité.

## Structure (rôles des classes)

### 1. **Strategy (Interface de stratégie)**
- Définit une interface commune pour tous les algorithmes
- Déclare la méthode que le contexte utilisera pour exécuter l'algorithme

### 2. **ConcreteStrategy (Stratégies concrètes)**
- Implémentent l'interface Strategy
- Chacune fournit une implémentation différente de l'algorithme
- Peuvent avoir leurs propres données et logiques internes

### 3. **Context (Contexte)**
- Maintient une référence vers un objet Strategy
- Ne connaît pas les détails de l'algorithme utilisé
- Délègue le travail à l'objet Strategy
- Peut permettre de changer la stratégie à l'exécution

## Avantages

✅ **Élimination des conditionnelles** : Remplace les structures if/else ou switch complexes  
✅ **Principe Ouvert/Fermé** : On peut ajouter de nouvelles stratégies sans modifier le code existant  
✅ **Isolation des algorithmes** : Chaque algorithme est dans sa propre classe, facile à tester et maintenir  
✅ **Flexibilité à l'exécution** : On peut changer de stratégie dynamiquement pendant l'exécution  
✅ **Réutilisabilité** : Les stratégies peuvent être réutilisées dans différents contextes  
✅ **Respect du SRP** : Chaque stratégie a une seule responsabilité

## Inconvénients

❌ **Multiplication des classes** : Chaque variante d'algorithme nécessite une nouvelle classe  
❌ **Complexité accrue** : Pour des cas simples, le pattern peut être trop lourd (over-engineering)  
❌ **Le client doit connaître les différences** : Le client doit savoir quelle stratégie choisir  
❌ **Overhead de communication** : Le contexte et la stratégie doivent partager des données  
❌ **Pas adapté si les algorithmes changent rarement** : Si vous n'avez que 2-3 variantes stables, un simple if/else peut suffire

## Cas d'usage réel possible

### 1. **Compression de fichiers**
- Différents algorithmes : ZIP, GZIP, BZIP2, compression rapide
- Choix selon le type de fichier, les besoins de compression ou la vitesse

### 2. **Export de données**
- Formats différents : JSON, XML, CSV, PDF
- Même données, représentations différentes selon le besoin

### 3. **Calcul de prix / Promotions**
- Stratégies de réduction : pourcentage, montant fixe, 2 pour 1, fidélité
- Application dynamique selon le profil client ou la période

### 4. **Validation de données**
- Différentes règles de validation selon le contexte
- Email, téléphone, code postal, formats personnalisés

### 5. **Tri et filtrage**
- Algorithmes de tri : rapide, fusion, bulles, insertion
- Choix selon la taille des données ou les contraintes mémoire

### 6. **Rendu graphique**
- Différents moteurs de rendu : 2D, 3D, vectoriel, raster
- Sélection selon les capacités du matériel ou les besoins visuels

### 7. **Chiffrement de données**
- Algorithmes variés : AES, RSA, Blowfish
- Choix selon le niveau de sécurité requis et les performances

