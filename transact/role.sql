BEGIN

-- Insertion des données dans la table role

INSERT INTO role(libelle) VALUES ('ROLE_API_LECTURE');
INSERT INTO role(libelle) VALUES ('ROLE_API_ECRITURE');
INSERT INTO role(libelle) VALUES ('ROLE_API_ADMIN');
INSERT INTO  role(libelle) VALUES ('ROLE_USER');
INSERT INTO  role(libelle) VALUES ('ROLE_GUEST');
INSERT INTO  role(libelle) VALUES ('ROLE_EMPLOYE');

COMMIT;


END;