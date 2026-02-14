# Guide de Validation - Application IH-AR

## 🚀 Démarrage rapide

### Étapes de démarrage
1. **Démarrer XAMPP**
   - Ouvrir XAMPP Control Panel
   - Démarrer **Apache**
   - Démarrer **MySQL**

2. **Vérifier la base de données**
   - Ouvrir phpMyAdmin : http://localhost/phpmyadmin
   - Vérifier que la base `hotux` existe
   - Si elle n'existe pas, l'importer depuis le fichier SQL

3. **Démarrer l'application**
   - Ouvrir un terminal dans le dossier du projet
   - Lancer le serveur Symfony :
     ```bash
     php -S localhost:8000 -t public
     ```
   - Ou configurer un VirtualHost dans Apache

4. **Accéder à l'application**
   - Ouvrir http://localhost:8000 dans le navigateur

## ✅ Vérifications avant la validation

### 1. Vérifier que XAMPP fonctionne
- Apache doit être démarré (bouton vert)
- MySQL doit être démarré (bouton vert)
- Vérifier dans phpMyAdmin que la base `hotux` existe

### 2. Vérifier l'application
- Ouvrir http://localhost:8000 dans le navigateur
- La page d'accueil doit s'afficher avec :
  - Logo "IH-AR"
  - Liste des chambres
  - Design moderne et attractif

### 3. Test de connexion Admin
- Aller sur http://localhost:8000/login
- Se connecter avec :
  - **Email** : `root@root.com`
  - **Mot de passe** : `admin123`
- Vérifier l'accès au dashboard admin avec :
  - ✅ Statistiques complètes (revenus, clients, rooms, events)
  - ✅ Boutons d'action rapide (Ajouter Room, Ajouter Event, etc.)
  - ✅ Menu avec sous-menus pour Rooms et Events

### 4. Test des fonctionnalités principales
- ✅ Page d'accueil : http://localhost:8000/home
- ✅ Liste des chambres : http://localhost:8000/room
- ✅ Liste des événements : http://localhost:8000/event
- ✅ Liste des services : http://localhost:8000/service
- ✅ Inscription : http://localhost:8000/register
- ✅ **Admin - Ajouter Room** : http://localhost:8000/admin/room/ajout
- ✅ **Admin - Ajouter Event** : http://localhost:8000/admin/event/ajout
- ✅ **Admin - Liste Users** : http://localhost:8000/admin/client_list
- ✅ **Admin - Dashboard** : http://localhost:8000/admin

## 📋 Informations pour la validation

### Architecture
- **Framework** : Symfony 6.x
- **Base de données** : MySQL/MariaDB (XAMPP)
- **Serveur web** : Apache (XAMPP) ou serveur PHP intégré
- **PHP** : 8.2+ (via XAMPP)

### Données de test
- **Chambres** : Plusieurs chambres disponibles
- **Admin** : root@root.com / admin123
- **Base de données** : hotux (MySQL)

### Personnalisation
- **Nom de l'hôtel** : IH-AR (au lieu de Hotux)
- **Copyright** : "Made by IHEBEDDINE SAAFI (2025)"
- **Email** : info@ih-ar.com

## 🔧 Commandes utiles pendant la validation

### Voir les logs
- Logs Apache : `C:\xampp\apache\logs\error.log`
- Logs Symfony : `var/log/dev.log`

### Vérifier la base de données
```bash
# Via phpMyAdmin : http://localhost/phpmyadmin
# Ou via ligne de commande :
mysql -u root -p hotux
```

### Vider le cache si problème
```bash
php bin/console cache:clear
```

### Redémarrer l'application
- Redémarrer Apache dans XAMPP
- Ou relancer : `php -S localhost:8000 -t public`

## 🐛 Résolution de problèmes rapide

### L'application ne charge pas
1. Vérifier que XAMPP est démarré (Apache et MySQL)
2. Vérifier le port (généralement 80 ou 8000)
3. Vérifier les logs Apache

### Erreur de connexion à la base
1. Vérifier que MySQL est démarré dans XAMPP
2. Vérifier que la base `hotux` existe dans phpMyAdmin
3. Vérifier la configuration dans `.env` :
   ```
   DATABASE_URL="mysql://root:@127.0.0.1:3306/hotux?serverVersion=10.4-MariaDB&charset=utf8mb4"
   ```

### Port occupé
1. Vérifier quel processus utilise le port :
   ```bash
   netstat -ano | findstr :8000
   ```
2. Changer le port dans la commande PHP ou dans Apache

## 📝 Points à présenter lors de la validation

1. **Application Symfony complète** : Framework moderne et structuré
2. **Base de données** : MySQL avec données complètes
3. **Personnalisation** : Logo, nom, copyright modifiés
4. **Fonctionnalités Admin** :
   - ✅ Dashboard avec statistiques complètes
   - ✅ Ajout/Modification/Suppression de Rooms
   - ✅ Ajout/Modification/Suppression d'Events
   - ✅ Gestion des utilisateurs (suppression)
   - ✅ Gestion des réservations
   - ✅ Gestion des services
5. **Interface** : Design moderne et responsive
6. **Sécurité** : Système d'authentification avec rôles (admin/client)

## 🎯 URL importantes

- **Application** : http://localhost:8000
- **Page d'accueil** : http://localhost:8000/home
- **Connexion** : http://localhost:8000/login
- **Inscription** : http://localhost:8000/register
- **Admin Dashboard** : http://localhost:8000/admin (après connexion)
- **Admin - Ajouter Room** : http://localhost:8000/admin/room/ajout
- **Admin - Ajouter Event** : http://localhost:8000/admin/event/ajout
- **Admin - Liste Users** : http://localhost:8000/admin/client_list

## ✅ Checklist finale avant validation

- [ ] XAMPP est démarré (Apache et MySQL)
- [ ] La base de données `hotux` existe et contient des données
- [ ] L'application est accessible sur http://localhost:8000
- [ ] La page d'accueil s'affiche correctement
- [ ] La connexion admin fonctionne (root@root.com / admin123)
- [ ] Le dashboard admin affiche les statistiques
- [ ] Les boutons d'action rapide fonctionnent
- [ ] Le menu admin avec sous-menus fonctionne
- [ ] L'ajout de room fonctionne
- [ ] L'ajout d'event fonctionne
- [ ] La suppression d'utilisateur fonctionne
- [ ] Les chambres sont affichées
- [ ] Le logo et le design sont corrects
- [ ] Le copyright est affiché

**Bonne chance pour la validation ! 🎉**
