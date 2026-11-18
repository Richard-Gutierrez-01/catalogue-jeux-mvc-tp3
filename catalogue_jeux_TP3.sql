-- =========================================================
-- Catalogue de jeux vidéo - TP3 (MVC + utilisateurs)
-- 582-31B-MA - Programmation Web avancée
-- =========================================================

DROP DATABASE IF EXISTS catalogue_jeux;
CREATE DATABASE catalogue_jeux CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE catalogue_jeux;

-- ---------------------------------------------------------
-- Table: developpeur
-- Côté "1" de la relation 1-à-plusieurs avec jeu
-- ---------------------------------------------------------
CREATE TABLE developpeur (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nom             VARCHAR(100) NOT NULL,
    pays_origine    VARCHAR(100),
    date_fondation  DATE
);

-- ---------------------------------------------------------
-- Table: jeu
-- Côté "plusieurs" de la relation avec developpeur (FK developpeur_id)
-- Participe aussi à la relation N-N avec plateforme
-- TP3 : colonne image (nom du fichier téléversé, NULL si aucune image)
-- ---------------------------------------------------------
CREATE TABLE jeu (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nom             VARCHAR(150) NOT NULL,
    description     TEXT,
    date_ajout      DATE,
    genre           VARCHAR(100),
    note            DECIMAL(3,1),
    date_sortie     DATE,
    image           VARCHAR(255) NULL,
    developpeur_id  INT,
    CONSTRAINT fk_jeu_developpeur FOREIGN KEY (developpeur_id) REFERENCES developpeur(id)
);

-- ---------------------------------------------------------
-- Table: plateforme
-- Participe à la relation N-N avec jeu
-- ---------------------------------------------------------
CREATE TABLE plateforme (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nom             VARCHAR(100) NOT NULL,
    description     TEXT,
    date_ajout      DATE,
    fabricant       VARCHAR(100),
    date_lancement  DATE
);

-- ---------------------------------------------------------
-- Table: jeu_plateforme
-- Table de jonction qui matérialise la relation N-N
-- entre jeu et plateforme
-- ---------------------------------------------------------
CREATE TABLE jeu_plateforme (
    jeu_id                  INT NOT NULL,
    plateforme_id           INT NOT NULL,
    date_sortie_plateforme  DATE,
    PRIMARY KEY (jeu_id, plateforme_id),
    CONSTRAINT fk_jeuplateforme_jeu FOREIGN KEY (jeu_id) REFERENCES jeu(id),
    CONSTRAINT fk_jeuplateforme_plateforme FOREIGN KEY (plateforme_id) REFERENCES plateforme(id)
);

-- ---------------------------------------------------------
-- TP3 - Table: privilege
-- Rôles : 1 = admin, 2 = client
-- ---------------------------------------------------------
CREATE TABLE privilege (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    privilege   VARCHAR(50) NOT NULL UNIQUE
);

-- ---------------------------------------------------------
-- TP3 - Table: user
-- Le mot de passe est toujours un hash bcrypt (jamais en clair)
-- ---------------------------------------------------------
CREATE TABLE user (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nom             VARCHAR(100) NOT NULL,
    username        VARCHAR(150) NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    privilege_id    INT NOT NULL,
    CONSTRAINT fk_user_privilege FOREIGN KEY (privilege_id) REFERENCES privilege(id)
);

-- ---------------------------------------------------------
-- TP3 - Table: log (journal de bord)
-- Une ligne par page visitée ; utilisateur = 'Visiteur' si non connecté
-- ---------------------------------------------------------
CREATE TABLE log (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    ip              VARCHAR(45) NOT NULL,
    date_visite     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    utilisateur     VARCHAR(150) NOT NULL DEFAULT 'Visiteur',
    page            VARCHAR(255) NOT NULL
);

-- =========================================================
-- Données
-- =========================================================

INSERT INTO developpeur (nom, pays_origine, date_fondation) VALUES
('CD Projekt Red', 'Pologne', '1994-01-01'),
('Bungie', 'États-Unis', '1991-05-01'),
('Lilith Games', 'Chine', '2013-01-01'),
('Iron Gate AB', 'Suède', '2019-03-01');
 
INSERT INTO jeu (nom, description, date_ajout, genre, note, date_sortie, developpeur_id) VALUES
('The Witcher 3', 'RPG en monde ouvert suivant Geralt de Riv.', CURDATE(), 'RPG', 9.5, '2015-05-19', 1),
('Destiny 2', 'Jeu de tir à la première personne en ligne gratuit.', CURDATE(), 'Action-mmo', 9.6, '2022-02-25', 2),
('Call of Dragons', 'Jeu de stratégie mobile en temps réel.', CURDATE(), 'Stratégie', 8.0, '2023-05-05', 3),
('Valheim', 'Jeu de survie theme Vikings.', CURDATE(), 'Survival', 9.4, '2026-09-09', 4);

INSERT INTO plateforme (nom, description, date_ajout, fabricant, date_lancement) VALUES
('PC', 'Plateforme de jeu sur ordinateur.', CURDATE(), 'Divers', NULL),
('PlayStation 5', 'Console de salon de 9e génération.', CURDATE(), 'Sony', '2020-11-12'),
('Android', 'Plateforme mobile.', CURDATE(), 'Google', '2008-09-23');

INSERT INTO jeu_plateforme (jeu_id, plateforme_id, date_sortie_plateforme) VALUES
(1, 1, '2015-05-19'),
(2, 1, '2022-02-25'),
(2, 2, '2022-02-25'),
(3, 3, '2023-05-05');

INSERT INTO privilege (id, privilege) VALUES
(1, 'admin'),
(2, 'client');

-- Comptes de démonstration (mots de passe hachés avec bcrypt)
-- admin@catalogue.ca  / Admin2026!
-- client@catalogue.ca / Client2026!
INSERT INTO user (nom, username, password, privilege_id) VALUES
('Administrateur', 'admin@catalogue.ca', '$2y$12$bmIWuSdxFmSZ6ANJ0bXpA.v15LtalFB0W.suruVa5vED28dmA8kLm', 1),
('Client Test', 'client@catalogue.ca', '$2y$12$.gvaZpEyvT.b5VposinyguUHoAkMd8.xdNvuwR0Q1SWRdiEEOUusi', 2);
