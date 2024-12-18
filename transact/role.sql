BEGIN

-- Insertion des données dans la table role

INSERT INTO role(libelle) VALUES ('api-lecture');
INSERT INTO role(libelle) VALUES ('api-ecriture');
INSERT INTO role(libelle) VALUES ('api-admin');
INSERT INTO  role(libelle) VALUES ('utilisateur');
INSERT INTO  role(libelle) VALUES ('visiteur');
INSERT INTO  role(libelle) VALUES ('employe');

COMMIT;


END;