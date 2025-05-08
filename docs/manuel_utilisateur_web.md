# 📘 Manuel Utilisateur – Application Web Cinéphoria

Bienvenue sur Cinéphoria, votre plateforme de réservation et de gestion de séances de cinéma. Ce manuel décrit l'utilisation de l'application Web selon les différents profils utilisateurs.

---

## 🎭 Rôles et fonctionnalités

| Rôle         | Fonctionnalités principales |
|--------------|------------------------------|
| Visiteur (Anonyme) | Visualiser les films, consulter les séances |
| Utilisateur authentifié | Réserver des billets, accéder à son espace personnel, laisser un avis |
| Employé     | Gérer les films, salles, séances |
| Administrateur | Même fonctions que l’employé + gestion des comptes employés |

---

## 🔐 Connexion

### Créer un compte
- Cliquez sur **Se connecter** puis **Créer un compte**
- Remplissez les champs : Email, mot de passe sécurisé (8 caractères minimum, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial), prénom, nom
- Vous recevrez un mail de confirmation pour activer votre compte

### Se connecter
- Cliquez sur **Se connecter**
- Entrez vos identifiants pour accéder à votre espace

---

## 🎟️ Réserver une séance (Utilisateur connecté)

1. Allez dans l’onglet **Réservation**
2. Choisissez :
   - Un **cinéma**
   - Un **film**
3. Sélectionnez une **séance** (en fonction de l'heure, la qualité, les places disponibles)
4. Choisissez vos **sièges** (PMR inclus si besoin)
5. Cliquez sur **Valider la réservation**

💡 Vos réservations sont visibles dans **Mon espace**

---

## 📝 Noter un film

1. Accédez à **Mon espace**
2. Si une séance est passée, un bouton **Noter ce film** est disponible
3. Laissez une **note (sur 5)** et une **description**
4. L’avis est soumis à validation par un employé avant publication

---

## 🎬 Consulter les films (Tous les utilisateurs)

- Cliquez sur **Films** pour voir l'affiche, le titre, la description, l’âge minimum, la note et le label *Coup de cœur*
- Filtrez les films par :
  - **Cinéma**
  - **Genre**
  - **Jour**
- Cliquez sur un film pour voir ses séances et le tarif selon la qualité

---

## 🛠️ Espace Employé

> Accessible après authentification avec un compte Employé

- Créer, modifier ou supprimer :
  - **Films**
  - **Séances**
  - **Salles** (places, qualité)

---

## 🛠️ Espace Administrateur

> Accessible après authentification avec un compte Administrateur

- Toutes les fonctionnalités Employé +
- Créer/modifier les **comptes Employés**
- Accéder au **tableau de bord des réservations** (sur les 7 derniers jours, depuis MongoDB)

---

## ❓ Mot de passe oublié

1. Cliquez sur **Mot de passe oublié**
2. Un mot de passe temporaire vous est envoyé par mail
3. Il sera à changer à la prochaine connexion

---

## 📩 Contacter le cinéma

> Disponible dans l’onglet **Contact**

- Renseignez :
  - Nom (facultatif)
  - Sujet
  - Description
- Le message est envoyé à un mail générique de Cinéphoria

---

## 👤 Identifiants de test

| Rôle          | Email                    | Mot de passe     |
|---------------|--------------------------|------------------|
| Admin         | admin@cinephoria.com     | Admin1234!       |

---

© Cinéphoria – Manuel Web Utilisateur