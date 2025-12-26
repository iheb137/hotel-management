# 🚀 Démarrer l'application après redémarrage du PC

## Méthode rapide (RECOMMANDÉ)

### Option 1 : Script automatique
1. **Double-clique sur `DEMARRER_APP.bat`**
2. Attends 10-15 secondes que MySQL démarre
3. L'application s'ouvrira automatiquement dans le navigateur

### Option 2 : Commandes manuelles
Ouvre PowerShell ou CMD dans le dossier du projet et exécute :

```bash
docker compose up -d
```

Attends 10-15 secondes, puis ouvre : **http://localhost:8081**

---

## ⏱️ Temps de démarrage

- **Docker Desktop** : 30-60 secondes (au premier démarrage)
- **MySQL** : 10-15 secondes
- **Total** : ~1-2 minutes maximum

**Note** : Si tu viens de redémarrer ton PC, Docker Desktop doit démarrer en premier.

---

## ✅ Vérification que tout fonctionne

### 1. Vérifier Docker Desktop
- Assure-toi que **Docker Desktop** est démarré (icône dans la barre des tâches)
- Si ce n'est pas le cas, lance Docker Desktop manuellement

### 2. Vérifier les conteneurs
```bash
docker compose ps
```

**Résultat attendu** : 3 conteneurs "Up"
- `true_project-db-1` (MySQL)
- `true_project-nginx-1` (Nginx)
- `true_project-php-1` (PHP)

### 3. Tester l'application
Ouvre ton navigateur sur : **http://localhost:8081**

---

## 🔧 Si ça ne démarre pas

### Problème : Docker Desktop n'est pas démarré
**Solution** : Lance Docker Desktop manuellement depuis le menu Démarrer

### Problème : Port 8081 occupé
```bash
# Arrêter les conteneurs
docker compose down

# Redémarrer
docker compose up -d
```

### Problème : Erreur de connexion à la base
```bash
# Attendre un peu plus (MySQL prend du temps)
# Puis vérifier
docker compose logs db
```

---

## 📝 Checklist rapide

- [ ] Docker Desktop est démarré
- [ ] J'ai exécuté `docker compose up -d` ou `DEMARRER_APP.bat`
- [ ] J'ai attendu 10-15 secondes
- [ ] J'ai ouvert http://localhost:8081 dans le navigateur

---

## 🎯 Résumé

**Après chaque redémarrage du PC :**

1. Lance **Docker Desktop** (si pas automatique)
2. Double-clique sur **`DEMARRER_APP.bat`**
   OU
   Exécute : `docker compose up -d`
3. Attends 10-15 secondes
4. Ouvre **http://localhost:8081**

**C'est tout ! 🎉**



