# Rapport PDF (Plan de captures)

## Pages requises

- Accueil du projet GitLab (nom, visibilité, membres)
- Variables CI/CD configurées (captures des champs)
- Runners actifs (Shared/Specific)
- Vue du pipeline (graph + statuts des jobs)
- Logs des jobs:
  - `composer_install`
  - `phpunit` (succès + artefact `junit.xml`)
  - `phpstan` (résultats)
  - `sonarqube` (si activé)
- Docker Hub: repository `ih-ar-app` avec tags `latest` et `commit SHA`
- Local:
  - `docker compose up -d` (capture des conteneurs Up)
  - Application sur `http://localhost:8081`
- Tests:
  - `php bin/phpunit` OK (statistiques)
  - Éventuels tests d’intégration/fonctionnels (ex. registration)
- Architecture:
  - Schéma des entités et relations (extrait des classes `Entity`)
  - Formulaires (extraits `Form/*`)

## Conseils

- Annoter chaque capture avec la démarche (objectif, résultat)
- Ajouter une section “Problèmes et résolutions” (erreurs JS, assets, DB tests)
- Conclure par “Livraison continue” et “Qualité de code” (SonarQube)

