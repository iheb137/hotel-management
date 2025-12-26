# CI/CD GitLab pour projet Symfony (IH-AR)

## Prérequis

- Dépôt GitLab avec Runner Docker disponible
- Compte Docker Hub
- Variables GitLab configurées dans `Settings > CI/CD > Variables`:
  - `DOCKERHUB_USERNAME`
  - `DOCKERHUB_TOKEN`
  - `SONAR_HOST_URL` (optionnel)
  - `SONAR_TOKEN` (optionnel)
  - `APP_ENV=test`

## Pipeline

- `validate`: installe les dépendances Composer et les met en cache
- `test`: lance PHPUnit avec la configuration `phpunit.xml.dist`
- `analyze`: exécute PHPStan (analyse statique) et SonarQube si variables présentes
- `build`: construit et pousse l'image Docker vers Docker Hub lors des merges sur `main`

## Docker local

```bash
docker compose up -d
open http://localhost:8081
```

Services: `php`, `nginx`, `db` (MariaDB 10.4). Port DB exposé sur `3307`.

## Déploiement Docker Hub

- L'image est poussée sous `DOCKERHUB_USERNAME/ih-ar-app` avec tags `latest` et `CI_COMMIT_SHORT_SHA`.

## SonarQube (bonus)

- Fichier `sonar-project.properties` inclus
- Job `sonarqube` activé si `SONAR_HOST_URL` et `SONAR_TOKEN` sont définis

## Sécurité

- Ne pas committer de secrets dans `.env`
- Déplacer `MAILER_DSN` et autres secrets dans les variables GitLab

