# 📚 Documentation Technique – Cinéphoria

Ce document présente les choix techniques, l’architecture et les éléments de conception de l’application web Cinéphoria.

---

## 🏛️ Architecture logicielle

### Choix des technologies

| Technologie | Rôle |
|-------------|------|
| Symfony 6   | Framework principal (backend MVC) |
| Twig        | Moteur de templates (frontend intégré) |
| jQuery      | Interactions dynamiques côté client |
| PostgreSQL       | Base de données relationnelle principale |
| Docker      | Conteneurisation de l’application |
| PhpStorm 2024 | Environnement de développement |
| GrumPHP     | Hook Git pour CI locale (pré-commit) |



### Fonctionnement global

- Application découpée en entités métiers : Film, Salle, Séance, Réservation, Utilisateur, etc.
- Le front-end (Twig + jQuery) est intégré dans Symfony.
- Doctrine gère l’ORM avec PostgreSQL.
- Application découpée par rôles (utilisateur, employé, admin).
- Sécurité assurée via le composant `Security` de Symfony avec firewalls & encodage.

---

## 🤔 Réflexions initiales

- Utilisation de Symfony pour accélérer la production, la gestion des entités et l’intégration facile de composants (Security, Doctrine). De plus cette technologie est celle que je maitrise le mieux.
- Choix de Twig pour séparer logique et vue tout en gardant un rendu server-side performant.
- Intégration de jQuery pour les interactions simples (sélection dynamique de séances/salles).
- Docker pour garantir un environnement stable et facilement déployable.

---

## ⚙️ Environnement de travail

- IDE : PhpStorm 2024
- Stack Docker : PHP 8.2, PostgreSQL, ngix
- Gestionnaire de paquets : Composer
- Gestion de version : Git + GitHub
- Outils de test : PHPUnit 
- Lint et pré-commit : GrumPHP

---

## 🗂️ Modèle Conceptuel de Données (MCD)

> Voir fichier `docs/mcd.png`


---

## 📊 Diagrammes

> Voir les fichiers dans `docs/`

- `diagramme_fonctionnalite.pdf` : Cas d’utilisation
- `diagramme_sequence.pdf` : Enchaînement logique des processus (ex : réservation)

---

## 🧪 Plan de test

### Objectif :
Assurer la robustesse des fonctionnalités critiques.

### Stratégie :
- Tests unitaires : validation des entités et services métiers
- Tests manuels : contrôle de l’UI/UX

### Exécution :
```bash
php bin/phpunit
```

---

## 🚀 Déploiement

### Local :
- `docker compose up -d`
- Création base : `cinephaliaapi`
- `doctrine:schema:update --force`

### Production :
- Déployé via hébergeur personnalisé
- Variables d’environnement via `.env`

---

## 🔁 CI/CD – GrumPHP

GrumPHP est utilisé comme hook local en pré-commit pour éviter les erreurs :

- Vérifie le code (lint PHP, YAML)
- Lance les tests unitaires
- Bloque les commits si les standards ne sont pas respectés

### Configuration :
```bash
composer require --dev phpro/grumphp
vendor/bin/grumphp git:pre-commit
```

---

© Cinéphoria – Documentation technique