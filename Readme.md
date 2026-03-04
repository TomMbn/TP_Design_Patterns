# 📚 Design Patterns en Orienté Objet

---

## 🏗 Structure

Pour chaque Design Pattern traité, vous trouverez un dossier dédié contenant :

1. **`explication.md`** : Une fiche de synthèse complète détaillant :
   - Le problème que le pattern résout
   - Son principe de fonctionnement (avec une analogie de la vie réelle)
   - La structure et le rôle des classes
   - Ses avantages et inconvénients
   - Des cas d'usage réels pertinents
2. **`exemple.php`** : Une implémentation concrète, commentée et exécutable, basée sur un scénario original (différent des exemples classiques comme les animaux ou les éditeurs graphiques).

---

## 📋 Patterns Implémentés

Voici la liste des 12 patterns documentés dans ce guide, classés par catégorie :

### 🔹 Patrons de Création
Gèrent les mécanismes de création d'objets pour augmenter la flexibilité et la réutilisation du code.

- [x] **Singleton** : Gestionnaire de configuration centralisé unique.
- [x] **Factory Method** : Système de création de notifications multi-canaux (Email, SMS, Push).
- [x] **Abstract Factory** : Écosystème d'objets IoT compatibles garantis (Maison Connectée).
- [x] **Builder** : Construction étape par étape de requêtes HTTP (API) avec interface fluide.

### 🔹 Patrons de Structure
Expliquent comment assembler des objets et des classes en de plus grandes structures tout en gardant ces structures flexibles et efficaces.

- [x] **Adapter** : Adaptation d'un système de logs legacy vers une interface moderne.
- [x] **Decorator** : Ajout dynamique d'équipement et de statistiques cumulables à un personnage de RPG.
- [x] **Facade** : Orchestration simplifiée d'un système complexe de déploiement DevOps (CI/CD).
- [x] **Composite** : Gestion d'une arborescence uniforme (bibliothèque musicale avec Chansons simples et Playlists imbriquées).

### 🔹 Patrons de Comportement
Gèrent les algorithmes et la répartition des responsabilités entre les objets.

- [x] **Strategy** : Sélection dynamique d'algorithmes de compression de fichiers à l'exécution.
- [x] **Observer** : Système d'actualités avec notifications automatiques aux abonnés.
- [x] **Command** : Système de transactions bancaires encapsulées avec historique permettant d'annuler (Undo) des actions.
- [x] **State** : Système d'alarme domotique réagissant différemment aux événements selon son état actuel (Armé, Désarmé, etc.).

---

## 🛠 Exécution

### Comment exécuter les exemples ?

Chaque pattern possède un fichier `exemple.php` autonome contenant la classe cliente et la démonstration.

**Depuis la racine du projet, lancez :**

```bash
php AbstractFactory/exemple.php
php Adapter/exemple.php
php Builder/exemple.php
php Command/exemple.php
php Composite/exemple.php
php Decorator/exemple.php
php Facade/exemple.php
php Factory/exemple.php
php Observer/exemple.php
php Singleton/exemple.php
php State/exemple.php
php Strategy/exemple.php
```

Alternativement, vous pouvez naviguer dans le dossier d'un pattern spécifique :

```bash
cd Observer
php exemple.php
```

---
