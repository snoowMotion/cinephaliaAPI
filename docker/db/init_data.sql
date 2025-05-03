-- Initialisation des cinémas
INSERT INTO cinephaliaapi.cinema(ville) VALUES
                               ('Lille'),
                               ('Paris'),
                               ('Grenoble');

-- Initialisation des genres
INSERT INTO cinephaliaapi.genre (libelle) VALUES
                                ('Action'),
                                ('Comédie'),
                                ('Horreur'),
                                ('Drame'),
                                ('Jeunesse');

-- Initialisation des qualités
INSERT INTO cinephaliaapi.qualite (libelle, prix) VALUES
                                        ('4D', 10.00),
                                        ('Max', 8.00),
                                        ('Classique', 7.00);

-- Initialisation des rôles
INSERT INTO cinephaliaapi.role (libelle) VALUES
                               ('ROLE_ADMIN'),
                               ('ROLE_GUEST'),
                               ('ROLE_USER'),
                               ('ROLE_EMPLOYE');

-- Création de l'utilisateur Admin
INSERT INTO cinephaliaapi.user (nom, prenom, email, password, is_confirmed,must_change_password) VALUES
    ('Admin', 'Admin', 'admin@cinephoria.cloud',
     '$2y$13$yflYd6KuWD0/ASpbNcMyxOcZq7BpRYPVta2Q9D5qkZnByZ8BoArHe', true, true);

-- Création du rôle Admin
INSERT INTO public.user_roles (user_id, role_id) VALUES
    (1, 1);