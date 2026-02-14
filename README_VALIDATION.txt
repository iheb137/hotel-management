================================================================================
                    APPLICATION IH-AR - PRÊTE POUR VALIDATION
================================================================================

🚀 DÉMARRAGE RAPIDE
================================================================================

1. Double-clique sur "DEMARRER_APP.bat"
   OU
   Exécute: docker compose up -d

2. Attends 10 secondes que MySQL démarre

3. Ouvre ton navigateur sur: http://localhost:8081

================================================================================
📋 INFORMATIONS IMPORTANTES
================================================================================

URL de l'application: http://localhost:8081

Connexion Admin:
  Email: root@root.com
  Password: admin123

Base de données:
  - Nom: hotux
  - Utilisateur: hotux
  - Mot de passe: hotux
  - Port: 3307 (exposé depuis Docker)

Personnalisation:
  - Nom: IH-AR
  - Copyright: Made by Ihebeddine saafi et arbi hazbri (ING-4-J-GLCI-C) - 2025
  - Email: info@ih-ar.com

================================================================================
✅ VÉRIFICATIONS AVANT VALIDATION
================================================================================

1. Docker Desktop est démarré
2. Exécute: docker compose ps
   → 3 conteneurs doivent être "Up"
3. Ouvre http://localhost:8081
   → La page d'accueil doit s'afficher
4. Teste la connexion admin
   → Doit accéder au dashboard

================================================================================
🔧 COMMANDES UTILES
================================================================================

Démarrer:          docker compose up -d
Arrêter:           docker compose down
Voir les logs:     docker compose logs -f
Redémarrer:        docker compose restart
Vider le cache:    docker compose exec php php bin/console cache:clear

================================================================================
📚 DOCUMENTATION
================================================================================

- VALIDATION_DEMAIN.md : Guide complet pour la validation
- DOCKER_GUIDE.md : Documentation Docker
- GUIDE_TEST.md : Guide de test de l'application

================================================================================
🎯 POINTS À PRÉSENTER
================================================================================

✓ Application entièrement containerisée avec Docker
✓ Base de données MySQL avec données importées
✓ Personnalisation complète (logo, nom, copyright)
✓ Interface moderne et responsive
✓ Système d'authentification avec rôles
✓ Gestion des chambres, événements, services, réservations

================================================================================

BONNE CHANCE POUR LA VALIDATION ! 🎉

IHEBEDDINE SAAFI (2025)



