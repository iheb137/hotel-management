# Hotel Management

Projet de gestion d'un hôtel développé avec Symfony PHP. Cette application gère les réservations, les chambres, les services et les utilisateurs (administrateurs, clients).

## Description
- **Technologies**: PHP, Symfony, Twig, Doctrine, JavaScript
- **Fonctionnalités principales**: gestion des chambres, réservations, utilisateurs, authentification, uploads d'assets, fixtures de tests.

## Installation (développement)
1. Copier le dépôt et installer les dépendances:

   composer install

2. Configurer les variables d'environnement (ex: `.env`) pour la base de données et le mailer.
3. Créer la base de données et exécuter les migrations / fixtures:

   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   php bin/console doctrine:fixtures:load

4. Lancer le serveur local:

   php -S 127.0.0.1:8000 -t public

## DevOps

- **Conteneurisation**: un `Dockerfile` et `docker-compose.yml` sont présents — utiliser `docker-compose up --build` pour démarrer les services (web, base de données, reverse-proxy si configuré).
- **Base de données**: projets prévus pour MySQL/MariaDB; les dumps et scripts d'import se trouvent à la racine (`hotux_backup.sql`, `hotux_backup_utf8.sql`).
- **CI/CD**: la base du projet contient des fichiers de configuration pour PHPUnit et d'autres outils (`phpunit.xml`, `phpunit.xml.dist`) — intégrer ces commandes dans la pipeline CI pour exécuter les tests unitaires et fonctionnels.
- **Migrations & Rollback**: utiliser Doctrine Migrations (`php bin/console doctrine:migrations:*`) pour versionner le schéma.
- **Backups & restauration**: des scripts `restore_database.bat` et `fix_all_database.php` sont fournis pour faciliter la restauration et la conversion d'encodage.
- **Logs & cache**: vérifier `var/log` et `var/cache` ; prévoir rotation des logs et nettoyage du cache avant déploiement.
- **Sécurité**: vérifier les accès, mettre en place HTTPS, et sécuriser les credentials via des secrets (env vars ou secret manager).

## Tests
- Les tests sont dans le dossier `tests/`. Exécuter `php vendor/bin/phpunit` pour lancer la suite.

## Contribution
- Merci de créer une branche par fonctionnalité et d'ouvrir une merge/pull request.

## Licence
Vérifier le fichier `composer.json` pour les informations de licence.
