# Pipeline GitLab CI/CD (Symfony + Docker)

## Étapes

1. Créer/importer le dépôt sur GitLab
2. Activer un Runner Docker (Shared ou propre)
3. Ajouter les variables CI/CD:
   - `DOCKERHUB_USERNAME`
   - `DOCKERHUB_TOKEN`
   - `SONAR_HOST_URL` (optionnel)
   - `SONAR_TOKEN` (optionnel)
4. Pousser la branche `main` avec `.gitlab-ci.yml`, `Dockerfile`, `docker-compose.yml`
5. Vérifier les jobs et les artefacts

## Stages

- `validate`: `composer install` (cache)
- `test`: `phpunit` avec `--log-junit junit.xml`
- `analyze`: `phpstan` et `sonar-scanner` (si variables définies)
- `build`: `docker build` + `docker push` vers Docker Hub (merge sur `main`)

## Runners

- Projet → `Settings` → `CI/CD` → `Runners`: activer `Shared Runners` ou enregistrer `docker runner`.

## Variables CI/CD

- Projet → `Settings` → `CI/CD` → `Variables`:
  - `DOCKERHUB_USERNAME` = votre compte
  - `DOCKERHUB_TOKEN` = token d’accès
  - `SONAR_HOST_URL` = URL instance SonarQube
  - `SONAR_TOKEN` = token Sonar

## Artefacts

- `phpunit` exporte `junit.xml` (visible dans l’onglet artifacts du job)

## Déploiement Docker Hub

- Le job `docker_build_push` construit `DOCKERHUB_USERNAME/ih-ar-app:latest` + `:SHA` et pousse sur Docker Hub lors des merges vers `main`.

