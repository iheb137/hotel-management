# Guide de Test - Application IH-AR

## 🚀 Accès à l'application

### Prérequis
- XAMPP installé et démarré
- Apache et MySQL actifs dans XAMPP
- Base de données `hotux` créée et importée

### URL principale
- **Application** : http://localhost:8000 (ou le port configuré dans XAMPP)
- **Page d'accueil** : http://localhost:8000/home
- **Profiler Symfony** : http://localhost:8000/_profiler (en mode dev)

## 📋 Tests à effectuer

### 1. Test de la page d'accueil
1. Ouvre ton navigateur
2. Va sur **http://localhost:8000**
3. Tu devrais être redirigé vers `/home`
4. Vérifie que :
   - Le logo "IH-AR" s'affiche correctement
   - Les chambres (rooms) sont affichées
   - Les événements (events) sont affichées
   - Les services sont affichés
   - Le design est moderne et attractif

### 2. Test de connexion (Admin)
1. Va sur **http://localhost:8000/login**
2. Connecte-toi avec :
   - **Email** : `root@root.com`
   - **Mot de passe** : `admin123`
3. Vérifie que tu accèdes au dashboard admin avec :
   - Les statistiques (revenus, nombre de clients, etc.)
   - Les boutons d'action rapide (Ajouter Room, Ajouter Event, etc.)
   - Le menu avec sous-menus pour Rooms et Events

### 3. Test de navigation
- **Chambres** : http://localhost:8000/room
- **Événements** : http://localhost:8000/event
- **Services** : http://localhost:8000/service
- **Réservations** : http://localhost:8000/reservation

### 4. Test de l'inscription
1. Va sur **http://localhost:8000/register**
2. Crée un nouveau compte client
3. Vérifie que l'inscription fonctionne

### 5. Test des fonctionnalités admin
Une fois connecté en tant qu'admin :
- ✅ Ajouter une chambre (via menu ou bouton d'action rapide)
- ✅ Ajouter un événement (via menu ou bouton d'action rapide)
- ✅ Voir la liste des rooms avec possibilité de modifier/supprimer
- ✅ Voir la liste des events avec possibilité de modifier/supprimer
- ✅ Voir la liste des utilisateurs avec possibilité de supprimer
- ✅ Voir les statistiques complètes dans le dashboard
- ✅ Gérer les réservations
- ✅ Gérer les services

## 🔧 Commandes utiles pour le débogage

### Vérifier la base de données
```bash
# Se connecter à MySQL via phpMyAdmin
# Ou via ligne de commande :
mysql -u root -p hotux
```

### Commandes Symfony
```bash
# Vider le cache
php bin/console cache:clear

# Voir les routes
php bin/console debug:router

# Voir les variables d'environnement
php bin/console debug:container --env-vars | grep DATABASE
```

### Vérifier la connexion à la base
```bash
# Tester la connexion Doctrine
php bin/console doctrine:query:sql "SELECT 1"
```

## 🐛 Résolution de problèmes

### Si l'application ne charge pas
1. Vérifie que XAMPP est démarré (Apache et MySQL)
2. Vérifie que le port est correct (généralement 80 ou 8000)
3. Vérifie les logs Apache dans XAMPP

### Si erreur de connexion à la base
1. Vérifie que MySQL est démarré dans XAMPP
2. Vérifie que la base `hotux` existe
3. Vérifie la configuration dans `.env` :
   ```
   DATABASE_URL="mysql://root:@127.0.0.1:3306/hotux?serverVersion=10.4-MariaDB&charset=utf8mb4"
   ```

### Si les images ne s'affichent pas
1. Vérifie que les fichiers sont dans `public/uploads/images/`
2. Vérifie les permissions du dossier

## ✅ Checklist de test complète

- [ ] Page d'accueil se charge correctement
- [ ] Logo et design s'affichent correctement
- [ ] Les chambres sont listées
- [ ] Les événements sont listées
- [ ] Les services sont listés
- [ ] Connexion admin fonctionne
- [ ] Dashboard admin affiche les statistiques
- [ ] Boutons d'action rapide fonctionnent dans le dashboard
- [ ] Menu admin avec sous-menus fonctionne
- [ ] Ajout de room fonctionne
- [ ] Ajout d'event fonctionne
- [ ] Suppression d'utilisateur fonctionne
- [ ] Inscription client fonctionne
- [ ] Navigation entre les pages fonctionne
- [ ] Les images s'affichent correctement
- [ ] Le footer avec copyright "IH-AR" s'affiche
- [ ] L'email de contact est `info@ih-ar.com`

## 📝 Notes importantes

- L'application est en mode **dev** (débogage activé)
- Le profiler Symfony est accessible sur chaque page
- Les modifications de code sont visibles immédiatement
- La base de données est dans XAMPP MySQL

Bon test ! 🎉
