-- Base de données du projet Hotel Reservation
-- A exécuter dans phpMyAdmin (onglet SQL). Les tables sont recréées à chaque exécution.

CREATE DATABASE IF NOT EXISTS hotel_resa_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE hotel_resa_db;

DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS chambres;
DROP TABLE IF EXISTS utilisateurs;

CREATE TABLE chambres (
    numero INT PRIMARY KEY,
    type   VARCHAR(20) NOT NULL,
    etage  INT         NOT NULL,
    prix   INT         NOT NULL,
    libre  TINYINT(1)  NOT NULL DEFAULT 1
);

CREATE TABLE utilisateurs (
    login        VARCHAR(30)  PRIMARY KEY,
    mot_de_passe VARCHAR(255) NOT NULL,
    nom          VARCHAR(60)  NOT NULL
);

CREATE TABLE reservations (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    login   VARCHAR(30) NOT NULL,
    chambre INT         NOT NULL,
    nuits   INT         NOT NULL,
    montant INT         NOT NULL
);

INSERT INTO chambres (numero, type, etage, prix, libre) VALUES
    (101, 'Simple', 1, 800,  1),
    (102, 'Double', 1, 1200, 0),
    (201, 'Simple', 2, 850,  1),
    (202, 'Double', 2, 1250, 1),
    (301, 'Suite',  3, 2500, 1);

-- mots de passe de démonstration : sara / s1234 et yassir / y5678 (stockés hachés)
INSERT INTO utilisateurs (login, mot_de_passe, nom) VALUES
    ('sara',   '$2y$10$jwl/IG.3amWC9WX/j.sPVe6tCMjvq0Ho2/89tW/civQoSQz94AldK', 'Sara Alami'),
    ('yassir', '$2y$10$mHKqLZrNVTUV9hJ9Fm/fquAlWxcGTZtzT1wXLFnjKNTXigb1SWyjq', 'Yassir Bennani');

-- la chambre 102 est déjà occupée par yassir
INSERT INTO reservations (login, chambre, nuits, montant) VALUES
    ('yassir', 102, 2, 2400);

-- compte MySQL de l'application (droits limités à cette base)
CREATE USER IF NOT EXISTS 'hotel_resa_user'@'localhost' IDENTIFIED BY 'hotel_resa_pass';
GRANT SELECT, INSERT, UPDATE, DELETE ON hotel_resa_db.* TO 'hotel_resa_user'@'localhost';
