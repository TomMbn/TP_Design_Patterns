# Observer (Observateur)

## Problème qu'il résout

Le pattern Observer résout le problème de la **notification automatique** lorsqu'un objet change d'état. Sans ce pattern, il faudrait vérifier manuellement et régulièrement si un objet a changé, ce qui est inefficace et crée un couplage fort entre les objets.

**Exemple du problème :** Imaginez un système de news où plusieurs utilisateurs veulent être notifiés dès qu'une nouvelle actualité est publiée. Sans Observer, chaque utilisateur devrait constamment vérifier s'il y a du nouveau contenu, ce qui est inefficace.

## Principe de fonctionnement

Le pattern Observer établit une **relation de dépendance un-à-plusieurs** entre objets :
- Un objet **Sujet** (Subject) maintient une liste d'**Observateurs** (Observers)
- Quand le Sujet change d'état, il **notifie automatiquement** tous ses Observateurs
- Chaque Observateur peut alors réagir au changement selon sa propre logique

**Analogie :** C'est comme un système d'abonnement à une newsletter. Quand un nouvel article est publié (changement d'état du sujet), tous les abonnés (observateurs) reçoivent automatiquement une notification.

## Structure (rôles des classes)

### 1. **Subject (Sujet)**
- Maintient la liste des observateurs
- Fournit des méthodes pour ajouter/retirer des observateurs (`attach()`, `detach()`)
- Notifie tous les observateurs lors d'un changement (`notify()`)

### 2. **Observer (Observateur)**
- Définit une interface de mise à jour (`update()`)
- Reçoit les notifications du sujet
- Réagit aux changements selon sa propre logique

### 3. **ConcreteSubject (Sujet Concret)**
- Implémente le Subject
- Stocke l'état qui intéresse les observateurs
- Déclenche la notification quand l'état change

### 4. **ConcreteObserver (Observateur Concret)**
- Implémente l'interface Observer
- Définit comment réagir aux notifications du sujet

## 📈 Avantages

✅ **Couplage faible** : Le sujet ne connaît pas les détails des observateurs, seulement leur interface  
✅ **Extensibilité** : On peut ajouter/retirer des observateurs dynamiquement sans modifier le sujet  
✅ **Réutilisabilité** : Les sujets et observateurs peuvent être réutilisés indépendamment  
✅ **Principe Ouvert/Fermé** : On peut introduire de nouveaux observateurs sans modifier le code existant  
✅ **Communication automatique** : Pas besoin de vérifier manuellement les changements

## ⚠️ Inconvénients

❌ **Ordre de notification non garanti** : Les observateurs sont notifiés dans un ordre arbitraire  
❌ **Fuites mémoire potentielles** : Si on oublie de détacher les observateurs, ils restent en mémoire  
❌ **Performance** : Si beaucoup d'observateurs sont attachés, les notifications peuvent être coûteuses  
❌ **Complexité de débogage** : Le flux d'exécution peut être difficile à suivre avec de nombreux observateurs  
❌ **Notifications en cascade** : Un observateur peut déclencher d'autres changements, créant des effets en chaîne

## 🧩 Cas d'usage réel possible

### 1. **Systèmes de notifications**
- Notifications push dans une application mobile
- Alertes email/SMS lors d'événements importants

### 2. **Interfaces utilisateur (MVC)**
- Mise à jour automatique de l'affichage quand les données changent
- Synchronisation entre plusieurs vues d'un même modèle

### 3. **Systèmes de monitoring**
- Surveillance de serveurs (CPU, mémoire, disque)
- Alertes automatiques quand des seuils sont dépassés

### 4. **Applications temps réel**
- Chat en ligne (nouveaux messages)
- Tableaux de bord financiers (cours de bourse)
- Systèmes de suivi de livraison

### 5. **Event-driven architecture**
- Systèmes de logs centralisés
- Systèmes de cache qui se mettent à jour automatiquement
