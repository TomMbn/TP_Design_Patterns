# Adapter (Adaptateur)

## Problème qu'il résout

Le pattern Adapter résout le problème de **l'incompatibilité entre interfaces**. Il permet de faire fonctionner ensemble des classes qui ne pourraient pas collaborer autrement à cause d'interfaces incompatibles.

**Exemple du problème :** Imaginez que vous avez une application qui utilise une bibliothèque de logs moderne, mais vous devez intégrer un ancien système de monitoring qui attend un format différent. Sans Adapter, vous devriez modifier soit votre code, soit la bibliothèque externe (souvent impossible), ou réécrire tout le système de logs.

## Principe de fonctionnement

Le pattern Adapter agit comme un **traducteur** entre deux interfaces incompatibles. Il enveloppe un objet existant et expose une nouvelle interface que le client attend, tout en traduisant les appels vers l'interface d'origine.

**Analogie :** C'est comme un adaptateur de prise électrique quand vous voyagez à l'étranger. Votre chargeur (client) a une prise française, mais la prise murale (service) est britannique. L'adaptateur permet de connecter les deux sans modifier ni le chargeur ni l'installation électrique.

Il existe deux types d'Adapter :
- **Adapter par composition** : L'adaptateur contient une instance de la classe à adapter
- **Adapter par héritage** : L'adaptateur hérite de la classe à adapter (moins flexible)

## Structure (rôles des classes)

### 1. **Target (Interface cible)**
- Définit l'interface que le client utilise
- C'est l'interface que le client s'attend à utiliser

### 2. **Adaptee (Classe à adapter)**
- La classe existante avec une interface incompatible
- Contient la logique métier utile mais inaccessible directement

### 3. **Adapter (Adaptateur)**
- Implémente l'interface Target
- Contient une référence vers un objet Adaptee
- Traduit les appels de l'interface Target vers l'interface Adaptee

### 4. **Client**
- Utilise l'interface Target
- Ne connaît pas l'existence de l'Adaptee ni de l'Adapter

## Avantages

✅ **Principe Ouvert/Fermé** : On peut introduire de nouveaux adaptateurs sans modifier le code existant  
✅ **Réutilisation de code existant** : Permet d'utiliser des classes existantes même si leur interface ne convient pas  
✅ **Séparation des responsabilités** : La logique de conversion est isolée dans l'adaptateur  
✅ **Intégration de bibliothèques tierces** : Facilite l'utilisation de code externe sans le modifier  
✅ **Flexibilité** : On peut adapter plusieurs classes différentes vers la même interface

## Inconvénients

❌ **Complexité accrue** : Ajoute une couche d'indirection supplémentaire  
❌ **Multiplication des classes** : Nécessite de créer un adaptateur pour chaque incompatibilité  
❌ **Performance** : Léger overhead dû à la traduction des appels  
❌ **Peut masquer des problèmes de conception** : Parfois, refactoriser serait plus approprié  
❌ **Maintenance** : Si l'interface Adaptee change, l'Adapter doit être mis à jour

## Cas d'usage réel possible

### 1. **Intégration de bibliothèques externes**
- Adapter une bibliothèque de logs externe vers votre interface standard
- Utiliser plusieurs fournisseurs de services (SMS, email) avec une interface unifiée

### 2. **Migration progressive de code legacy**
- Adapter l'ancien code vers la nouvelle architecture
- Permettre la coexistence de l'ancien et du nouveau système

### 3. **Abstraction de services tiers**
- Adapter différentes APIs de paiement (Stripe, PayPal) vers une interface commune
- Unifier plusieurs services de stockage cloud (AWS S3, Google Cloud, Azure)

### 4. **Conversion de formats de données**
- Adapter XML vers JSON ou vice versa
- Convertir entre différents formats de dates, devises, unités

### 5. **Compatibilité multi-plateforme**
- Adapter les APIs système différentes (Windows, Linux, macOS)
- Unifier les interfaces de bases de données (MySQL, PostgreSQL, MongoDB)

### 6. **Tests et mocks**
- Créer des adaptateurs pour faciliter les tests unitaires
- Simuler des services externes pendant le développement

### 7. **Internationalisation**
- Adapter différents systèmes de mesure (métrique, impérial)
- Convertir entre différents formats de numéros de téléphone, adresses
