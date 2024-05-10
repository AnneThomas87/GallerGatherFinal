CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    email VARCHAR(180) UNIQUE NOT NULL,
    roles JSON NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
) ENGINE=InnoDB;

SELECT * FROM user;

SELECT * FROM user WHERE id = :userId;

INSERT INTO category (name) VALUES ('Nouvelle Catégorie');

UPDATE category SET name = 'Nouveau Nom' WHERE id = :categoryId;

DELETE FROM category WHERE id =:categoryId;

SELECT evenements.*, lieux.nom AS nom_lieu, lieux.adresse
FROM evenements
INNER JOIN lieux ON evenements.lieu_id = lieux.id;