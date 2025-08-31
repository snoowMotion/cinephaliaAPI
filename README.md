# 🎬 Cinéphoria

Cinéphoria est une plateforme complète de gestion de cinémas, permettant aux utilisateurs de consulter les séances, réserver des billets, noter les films, et aux employés de gérer l'infrastructure et les incidents.

## 📌 Sommaire

- [Présentation](#présentation)
- [Fonctionnalités](#fonctionnalités)
- [Architecture du projet](#architecture-du-projet)
- [Installation](#installation)
- [Utilisation](#utilisation)
- [Stack technique](#stack-technique)
- [Tests](#tests)
- [Déploiement](#déploiement)
- [Conventions Git](#conventions-git)
- [Liens utiles](#liens-utiles)

---

## 📖 Présentation

Cinéphoria est un projet réalisé dans le cadre du TP "Concepteur Développeur d’Applications". Il s'agit d'une suite applicative répartie sur trois supports :

- **Web** : Réservations, notation, gestion.
- **Mobile** : Affichage des billets et QR Codes.
- **Bureautique** : Déclaration d’incidents en salle.

Entreprise fictive : **Cinéphoria**, acteur majeur du cinéma en France et en Belgique.

## ✨ Fonctionnalités

### Application Web
- Affichage des séances par cinéma et horaire
- Réservation de billets avec choix des sièges
- Affichage des films par genre, cinéma et jour
- Authentification (utilisateur, employé, admin)
- Gestion des films, salles et séances (admin/employé)
- Notation des films, validation d’avis
- Tableau de bord des réservations (admin)

### Application Mobile
- Visualisation des billets du jour
- Affichage du QR Code des billets

### Application Bureautique
- Déclaration et consultation d’incidents techniques

## ⚙️ Installation

### Pré-requis
- PHP ≥ 8.1
- Composer
- Docker


### Étapes

1. Clonez le dépôt :
   ```bash
   git clone https://github.com/snoowMotion/cinephaliaAPI.git
   cd cinephaliaAPI
   ```

2. Lancez les conteneurs Docker :
   ```bash
   docker compose up -d --build
   ```
3. Créer la base de données :
   ```bash
    sudo docker compose exec db psql -U cinephaliadoctrine -d cinephalia
    CREATE DATABASE cinephaliaapi WITH OWNER cinephaliadoctrine ENCODING 'UTF8';
    ```

4. Mettez à jour le schéma de la base de données avec Doctrine :
   ```bash
   docker exec -it cinephalia_php php bin/console doctrine:schema:update --force
    ```
5. Lancez le script de remplissage de la base de données :
   ```bash
   sudo docker compose exec -T db psql cinephaliadoctrine -d cinephalia < docker/db/init_data.sql
   ```
6. Configurez le .env avec les informations suivantes : 
    ```dotenv
   APP_ENV=dev
   APP_SECRET=monsecret
   APP_URL=monurl
   MONGODB_URL=connectionStringMongodb
   MONGODB_DB=cinephalia
   DATABASE_URL=connectionStringDatabase
   CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$'
   JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
   JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
   JWT_PASSPHRASE=cinephalia
   JWT_TOKEN_TTL=3600
   MAILJET_API_KEY=maCléMailJet
   MAILJET_API_SECRET=MonApiSecretMailJet
   MAILJET_FROM_EMAIL=noresponse@cinephoria.cloud
   MAILJET_FROM_NAME=Cinéphoria
   MAILER_DSN=null://null
    ```
6. Accédez à l'application via `http://localhost:8000/login`


## 🛠️ Stack technique

- **Frontend** : HTML5, CSS3 (Bootstrap), JQuery, Twig
- **Backend** : Symfony 6
- **BDD relationnelle** : PgSQL

## 🧑‍💻 Conventions Git

- `master` : branche stable, déployée
- `dev` : branche de développement
- `feature/*` : une branche par fonctionnalité


## 🔗 Liens utiles

- 📁 [Charte graphique]([https://cinephoriastudi.atlassian.net/wiki/spaces/CW/pages/65869/Charte+Graphique+pour+le+Site+Web+Cinephoria])
- 📁 [Manuel utilisateur](./docs/manuel_utilisateur_web.md)
- 📁 [Documentation Gestion projet](./docs/documentation_gestion_projet.md)
- ✅ [Jira](https://cinephoriastudi.atlassian.net/jira/software/projects/KAN/boards/1)

---

© Projet Cinéphoria - TP CDA Studi
