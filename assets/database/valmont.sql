CREATE DATABASE IF NOT EXISTS valmont
USE valmont

-- Table UTILISATEUR
CREATE TABLE UTILISATEUR (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    date_naissance DATE,
    sexe VARCHAR(20),
    age INT,
    email VARCHAR(100) NOT NULL UNIQUE,
    type_membre VARCHAR(50),
    photo_url VARCHAR(255),
    type_utilisateur ENUM('visiteur', 'simple', 'complexe', 'administrateur') NOT NULL,
    niveau_experience ENUM('débutant', 'intermédiaire', 'avancé', 'expert') DEFAULT 'débutant',
    points_connexion FLOAT DEFAULT 0,
    points_consultation FLOAT DEFAULT 0,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    compte_valide BOOLEAN DEFAULT FALSE
);

-- Table HISTORIQUE_ACTIONS
CREATE TABLE HISTORIQUE_ACTIONS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    date_action DATETIME DEFAULT CURRENT_TIMESTAMP,
    type_action VARCHAR(50) NOT NULL,
    details TEXT,
    FOREIGN KEY (utilisateur_id) REFERENCES UTILISATEUR(id) ON DELETE CASCADE
);

-- Table OBJET_CONNECTE
CREATE TABLE OBJET_CONNECTE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_unique VARCHAR(50) NOT NULL UNIQUE,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    type VARCHAR(50) NOT NULL,
    marque VARCHAR(50),
    etat ENUM('Actif', 'Inactif', 'Connecté', 'Déconnecté', 'Maintenance') DEFAULT 'Actif',
    localisation VARCHAR(100),
    derniere_interaction DATETIME,
    connectivite VARCHAR(50),
    batterie_pct INT,
    actif BOOLEAN DEFAULT TRUE
);

-- Table CAMERA_SURVEILLANCE
CREATE TABLE CAMERA_SURVEILLANCE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    objet_id INT NOT NULL,
    resolution VARCHAR(20),
    detection_mouvement BOOLEAN DEFAULT FALSE,
    vision_nocturne BOOLEAN DEFAULT FALSE,
    angle_vision VARCHAR(20),
    FOREIGN KEY (objet_id) REFERENCES OBJET_CONNECTE(id) ON DELETE CASCADE
);

-- Table COMPTEUR_ELECTRICITE
CREATE TABLE COMPTEUR_ELECTRICITE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    objet_id INT NOT NULL,
    consommation_actuelle FLOAT,
    consommation_journaliere FLOAT,
    consommation_mensuelle FLOAT,
    puissance_max FLOAT,
    FOREIGN KEY (objet_id) REFERENCES OBJET_CONNECTE(id) ON DELETE CASCADE
);

-- Table PARKING_INTELLIGENT
CREATE TABLE PARKING_INTELLIGENT (
    id INT AUTO_INCREMENT PRIMARY KEY,
    objet_id INT NOT NULL,
    places_totales INT,
    places_disponibles INT,
    localisation_precise VARCHAR(100),
    FOREIGN KEY (objet_id) REFERENCES OBJET_CONNECTE(id) ON DELETE CASCADE
);

-- Table CAPTEUR_BRUIT
CREATE TABLE CAPTEUR_BRUIT (
    id INT AUTO_INCREMENT PRIMARY KEY,
    objet_id INT NOT NULL,
    niveau_decibel FLOAT,
    seuil_alerte FLOAT,
    derniere_alerte DATETIME,
    FOREIGN KEY (objet_id) REFERENCES OBJET_CONNECTE(id) ON DELETE CASCADE
);

-- Table ABRIBUS_INTELLIGENT
CREATE TABLE ABRIBUS_INTELLIGENT (
    id INT AUTO_INCREMENT PRIMARY KEY,
    objet_id INT NOT NULL,
    prochains_passages TEXT,
    ecran_fonctionnel BOOLEAN DEFAULT TRUE,
    informations_affichees TEXT,
    FOREIGN KEY (objet_id) REFERENCES OBJET_CONNECTE(id) ON DELETE CASCADE
);

-- Table CAPTEUR_NIVEAU_EAU
CREATE TABLE CAPTEUR_NIVEAU_EAU (
    id INT AUTO_INCREMENT PRIMARY KEY,
    objet_id INT NOT NULL,
    niveau_actuel FLOAT,
    seuil_alerte FLOAT,
    alerte_active BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (objet_id) REFERENCES OBJET_CONNECTE(id) ON DELETE CASCADE
);

-- Table DONNEES_CAPTEUR
CREATE TABLE DONNEES_CAPTEUR (
    id INT AUTO_INCREMENT PRIMARY KEY,
    objet_id INT NOT NULL,
    horodatage DATETIME DEFAULT CURRENT_TIMESTAMP,
    valeurs JSON,
    type_donnee VARCHAR(50),
    FOREIGN KEY (objet_id) REFERENCES OBJET_CONNECTE(id) ON DELETE CASCADE
);

-- Table EVENEMENT
CREATE TABLE EVENEMENT (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    lieu VARCHAR(100),
    places_max INT,
    prix FLOAT DEFAULT 0,
    inscription_requise BOOLEAN DEFAULT FALSE,
    type_evenement VARCHAR(50)
);

-- Table INSCRIPTION_EVENEMENT
CREATE TABLE INSCRIPTION_EVENEMENT (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evenement_id INT NOT NULL,
    utilisateur_id INT NOT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('confirmé', 'en attente', 'annulé') DEFAULT 'en attente',
    details_supplementaires JSON,
    FOREIGN KEY (evenement_id) REFERENCES EVENEMENT(id) ON DELETE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES UTILISATEUR(id) ON DELETE CASCADE
);

-- Table HORAIRE_VISITE
CREATE TABLE HORAIRE_VISITE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evenement_id INT NOT NULL,
    heure_debut DATETIME NOT NULL,
    heure_fin DATETIME NOT NULL,
    places_disponibles INT,
    guide VARCHAR(100),
    FOREIGN KEY (evenement_id) REFERENCES EVENEMENT(id) ON DELETE CASCADE
);

-- Table SERVICE
CREATE TABLE SERVICE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    type_service VARCHAR(50),
    actif BOOLEAN DEFAULT TRUE
);

-- Insertion d'utilisateurs de test
INSERT INTO UTILISATEUR (login, mot_de_passe, nom, prenom, date_naissance, sexe, age, email, type_membre, type_utilisateur, niveau_experience, compte_valide) VALUES
-- Administrateur
('admin', '$2y$10$rWoQxZ3GD7n5qK3Xt54eEOubHwj5GWIBzeZzA1NeGxJ5d0MyTpZTq', 'Admin', 'Principal', '1985-05-15', 'Homme', 38, 'admin@valmont.fr', 'Responsable', 'administrateur', 'expert', TRUE),

-- Utilisateurs complexes
('martin_d', '$2y$10$3DvK3UtSeC0Lh6QKArCjJeH4Uw6j9ZZmZr/rMZK3QisK7r9ZDGCqq', 'Dupont', 'Martin', '1990-03-20', 'Homme', 33, 'martin.dupont@valmont.fr', 'Technicien', 'complexe', 'avancé', TRUE),
('sophie_l', '$2y$10$n4tDEwl8FrJlsz/kJySBheKcV.vHdDX8M7TmFYxOpbG7yxCYE1CZG', 'Laurent', 'Sophie', '1988-07-12', 'Femme', 35, 'sophie.laurent@valmont.fr', 'Ingénieure', 'complexe', 'avancé', TRUE),

-- Utilisateurs simples
('julie_m', '$2y$10$lKjYTFl/F.d1k4GfjHdxpu9HQBR/0Dvsx0SxNhCRVTMH3g/t20vgW', 'Martin', 'Julie', '1995-11-05', 'Femme', 28, 'julie.martin@valmont.fr', 'Habitante', 'simple', 'intermédiaire', TRUE),
('thomas_b', '$2y$10$JMUuRhHwDvMM8jKr4KnZFO1D6K4OTtU0FjDmUSJ8mwYj0vMd8ArHu', 'Bernard', 'Thomas', '1992-08-30', 'Homme', 31, 'thomas.bernard@valmont.fr', 'Habitant', 'simple', 'débutant', TRUE),
('emma_r', '$2y$10$y9a3R.5oWBfvkN78YvTTCuKQD8YVEVfdlQasr5DNw20G/x9BvK6vu', 'Robert', 'Emma', '1998-02-15', 'Femme', 25, 'emma.robert@valmont.fr', 'Étudiante', 'simple', 'débutant', TRUE),
('lucas_p', '$2y$10$QYqUGEvkg1XHqZTF2qOvmeVcg16FXmYkN1qTj.XD.kTFmZBmcPjye', 'Petit', 'Lucas', '1991-12-03', 'Homme', 32, 'lucas.petit@valmont.fr', 'Commerçant', 'simple', 'intermédiaire', TRUE),
('chloe_d', '$2y$10$7xKCUUl4jRp1M.2ZpfS5SOlqBEj6RflrGC7Bs9PnYF5XCqFJhkpnm', 'Dubois', 'Chloé', '1996-09-22', 'Femme', 27, 'chloe.dubois@valmont.fr', 'Enseignante', 'simple', 'débutant', TRUE);

-- Insertion des historiques d'actions pour simuler l'activité des utilisateurs
INSERT INTO HISTORIQUE_ACTIONS (utilisateur_id, type_action, details) VALUES
(1, 'connexion', 'Connexion administrateur'),
(1, 'gestion_objets', 'Ajout de nouveaux capteurs'),
(2, 'connexion', 'Connexion utilisateur'),
(2, 'consultation_objets', 'Consultation des caméras de surveillance'),
(3, 'connexion', 'Connexion utilisateur'),
(3, 'consultation_objets', 'Vérification des places de stationnement'),
(4, 'connexion', 'Connexion utilisateur'),
(4, 'consultation_evenements', 'Consultation des événements à venir'),
(5, 'connexion', 'Connexion utilisateur'),
(5, 'recherche', 'Recherche informations sur les transports');

-- Insertion des objets connectés
INSERT INTO OBJET_CONNECTE (id_unique, nom, description, type, marque, etat, localisation, derniere_interaction, connectivite, batterie_pct, actif) VALUES
-- Caméras
('CAM001', 'Caméra Place Centrale', 'Caméra de surveillance HD couvrant la place centrale', 'caméra', 'SecurityPlus', 'Actif', 'Place Centrale', NOW(), 'Wi-Fi', 85, TRUE),
('CAM002', 'Caméra Entrée Mairie', 'Caméra de surveillance à l\'entrée de la mairie', 'caméra', 'SecurityPlus', 'Actif', 'Mairie', NOW(), 'Wi-Fi', 90, TRUE),
('CAM003', 'Caméra Parc Lidia Poêt', 'Caméra de surveillance du parc', 'caméra', 'SecurityPlus', 'Actif', 'Parc Lidia Poêt', NOW(), 'Wi-Fi', 75, TRUE),

-- Compteurs électricité
('ELEC001', 'Compteur Électrique Mairie', 'Compteur intelligent pour la consommation de la mairie', 'compteur électricité', 'EnergyTrack', 'Actif', 'Mairie', NOW(), 'GSM', 100, TRUE),
('ELEC002', 'Compteur Électrique Bibliothèque', 'Compteur intelligent pour la bibliothèque municipale', 'compteur électricité', 'EnergyTrack', 'Actif', 'Bibliothèque', NOW(), 'GSM', 100, TRUE),

-- Parkings intelligents
('PARK001', 'Parking Centre-Ville', 'Système de gestion des places du parking central', 'parking', 'SmartPark', 'Actif', 'Centre-Ville', NOW(), 'GSM', 100, TRUE),
('PARK002', 'Parking Gare', 'Système de gestion des places près de la gare', 'parking', 'SmartPark', 'Actif', 'Gare', NOW(), 'GSM', 100, TRUE),

-- Capteurs de bruit
('BRUIT001', 'Capteur Sonore Zone Festive', 'Mesure le niveau sonore dans la zone des bars', 'capteur bruit', 'SoundSense', 'Actif', 'Rue des Bars', NOW(), 'LoRa', 65, TRUE),
('BRUIT002', 'Capteur Sonore Zone Résidentielle', 'Surveille les nuisances sonores en zone résidentielle', 'capteur bruit', 'SoundSense', 'Actif', 'Quartier Est', NOW(), 'LoRa', 70, TRUE),

-- Abribus intelligents
('BUS001', 'Abribus Centre', 'Abribus avec panneau d\'information en temps réel', 'abribus', 'TransitTech', 'Actif', 'Centre-Ville', NOW(), 'Fibre', 100, TRUE),
('BUS002', 'Abribus Gare', 'Abribus avec panneau d\'information en temps réel', 'abribus', 'TransitTech', 'Actif', 'Gare', NOW(), 'Fibre', 100, TRUE),

-- Capteurs niveau d'eau
('EAU001', 'Capteur Rivière Nord', 'Surveille le niveau de la rivière en amont de la ville', 'capteur eau', 'HydroSense', 'Actif', 'Rivière Nord', NOW(), 'LoRa', 80, TRUE),
('EAU002', 'Capteur Rivière Sud', 'Surveille le niveau de la rivière en aval de la ville', 'capteur eau', 'HydroSense', 'Actif', 'Rivière Sud', NOW(), 'LoRa', 85, TRUE);

-- Insertion des données spécifiques pour chaque type d'objet
-- Caméras
INSERT INTO CAMERA_SURVEILLANCE (objet_id, resolution, detection_mouvement, vision_nocturne, angle_vision) VALUES
(1, '1080p', TRUE, TRUE, '120°'),
(2, '1080p', TRUE, TRUE, '90°'),
(3, '720p', TRUE, FALSE, '180°');

-- Compteurs électricité
INSERT INTO COMPTEUR_ELECTRICITE (objet_id, consommation_actuelle, consommation_journaliere, consommation_mensuelle, puissance_max) VALUES
(4, 12.5, 280.3, 8500.0, 50.0),
(5, 8.2, 175.5, 5200.0, 35.0);

-- Parkings
INSERT INTO PARKING_INTELLIGENT (objet_id, places_totales, places_disponibles, localisation_precise) VALUES
(6, 150, 42, 'Rue Victor Hugo, Centre-Ville'),
(7, 80, 15, 'Place de la Gare');

-- Capteurs de bruit
INSERT INTO CAPTEUR_BRUIT (objet_id, niveau_decibel, seuil_alerte, derniere_alerte) VALUES
(8, 65.8, 85.0, '2025-04-13 23:30:00'),
(9, 48.2, 70.0, '2025-04-14 02:15:00');

-- Abribus
INSERT INTO ABRIBUS_INTELLIGENT (objet_id, prochains_passages, ecran_fonctionnel, informations_affichees) VALUES
(10, 'Ligne 1: 5min, Ligne 3: 12min', TRUE, 'Météo: Ensoleillé, Température: 18°C, Actualités: Festival ce weekend'),
(11, 'Ligne 2: 3min, Ligne 5: 8min', TRUE, 'Météo: Ensoleillé, Température: 18°C, Actualités: Festival ce weekend');

-- Capteurs niveau d'eau
INSERT INTO CAPTEUR_NIVEAU_EAU (objet_id, niveau_actuel, seuil_alerte, alerte_active) VALUES
(12, 2.3, 4.5, FALSE),
(13, 2.1, 4.5, FALSE);

-- Insertion des données capteurs
INSERT INTO DONNEES_CAPTEUR (objet_id, horodatage, valeurs, type_donnee) VALUES
(1, NOW() - INTERVAL 1 HOUR, '{"mouvement": false, "personnes": 0}', 'détection'),
(1, NOW() - INTERVAL 30 MINUTE, '{"mouvement": true, "personnes": 3}', 'détection'),
(1, NOW(), '{"mouvement": true, "personnes": 5}', 'détection'),

(4, NOW() - INTERVAL 2 HOUR, '{"consommation": 11.2}', 'énergie'),
(4, NOW() - INTERVAL 1 HOUR, '{"consommation": 12.5}', 'énergie'),
(4, NOW(), '{"consommation": 13.1}', 'énergie'),

(6, NOW() - INTERVAL 3 HOUR, '{"places_disponibles": 65}', 'stationnement'),
(6, NOW() - INTERVAL 2 HOUR, '{"places_disponibles": 50}', 'stationnement'),
(6, NOW() - INTERVAL 1 HOUR, '{"places_disponibles": 45}', 'stationnement'),
(6, NOW(), '{"places_disponibles": 42}', 'stationnement'),

(8, NOW() - INTERVAL 4 HOUR, '{"decibels": 58.3}', 'bruit'),
(8, NOW() - INTERVAL 2 HOUR, '{"decibels": 62.7}', 'bruit'),
(8, NOW(), '{"decibels": 65.8}', 'bruit'),

(12, NOW() - INTERVAL 12 HOUR, '{"niveau": 2.1}', 'niveau_eau'),
(12, NOW() - INTERVAL 6 HOUR, '{"niveau": 2.2}', 'niveau_eau'),
(12, NOW(), '{"niveau": 2.3}', 'niveau_eau');

-- Insertion des événements
INSERT INTO EVENEMENT (nom, description, date_debut, date_fin, lieu, places_max, prix, inscription_requise, type_evenement) VALUES
('Concert de Jazz au Parc', 'Profitez d\'une soirée jazz en plein air avec des artistes locaux', '2025-05-15 19:00:00', '2025-05-15 22:00:00', 'Parc Lidia Poêt', 200, 5.00, TRUE, 'concert'),
('Exposition sur la Ville de Valmont', 'Découvrez l\'histoire et le patrimoine de notre belle ville', '2025-04-20 09:00:00', '2025-04-30 17:00:00', 'Musée Municipal', 50, 3.50, TRUE, 'exposition'),
('Festival des Talents', 'Venez montrer vos talents ou applaudir les artistes locaux', '2025-05-22 14:00:00', '2025-05-22 23:00:00', 'Place Centrale', 300, 0.00, TRUE, 'festival'),
('Chasse aux Œufs de Pâques', 'Grande chasse aux œufs pour les enfants de la ville', '2025-04-21 10:00:00', '2025-04-21 12:00:00', 'Parc Municipal', 100, 0.00, TRUE, 'événement familial');

-- Insertion des horaires de visite pour l'exposition
INSERT INTO HORAIRE_VISITE (evenement_id, heure_debut, heure_fin, places_disponibles, guide) VALUES
(2, '2025-04-20 09:00:00', '2025-04-20 09:45:00', 10, 'Marie Durand'),
(2, '2025-04-20 10:00:00', '2025-04-20 10:45:00', 10, 'Marie Durand'),
(2, '2025-04-20 11:00:00', '2025-04-20 11:45:00', 10, 'Marie Durand'),
(2, '2025-04-20 14:00:00', '2025-04-20 14:45:00', 10, 'Marie Durand'),
(2, '2025-04-20 15:00:00', '2025-04-20 15:45:00', 10, 'Marie Durand'),
(2, '2025-04-20 16:00:00', '2025-04-20 16:45:00', 10, 'Marie Durand'),
(2, '2025-04-21 09:00:00', '2025-04-21 09:45:00', 10, 'Marie Durand'),
(2, '2025-04-21 10:00:00', '2025-04-21 10:45:00', 10, 'Marie Durand'),
(2, '2025-04-21 11:00:00', '2025-04-21 11:45:00', 10, 'Marie Durand'),
(2, '2025-04-21 14:00:00', '2025-04-21 14:45:00', 10, 'Marie Durand'),
(2, '2025-04-21 15:00:00', '2025-04-21 15:45:00', 10, 'Marie Durand'),
(2, '2025-04-21 16:00:00', '2025-04-21 16:45:00', 10, 'Marie Durand');

-- Insertion des inscriptions événements
INSERT INTO INSCRIPTION_EVENEMENT (evenement_id, utilisateur_id, date_inscription, statut, details_supplementaires) VALUES
-- Inscriptions au concert
(1, 3, '2025-04-10 15:30:00', 'confirmé', '{"nombre_places": 2}'),
(1, 4, '2025-04-11 09:15:00', 'confirmé', '{"nombre_places": 1}'),
(1, 5, '2025-04-12 14:45:00', 'confirmé', '{"nombre_places": 3}'),
-- Inscriptions à l'exposition
(2, 4, '2025-04-09 10:20:00', 'confirmé', '{"horaire_visite_id": 3, "nombre_places": 2}'),
(2, 7, '2025-04-10 16:30:00', 'confirmé', '{"horaire_visite_id": 7, "nombre_places": 1}'),
-- Inscriptions au festival des talents
(3, 5, '2025-04-08 18:45:00', 'confirmé', '{"participant": true, "talent": "Chant", "heure_passage": "16:30"}'),
(3, 6, '2025-04-09 11:10:00', 'confirmé', '{"participant": false}'),
(3, 8, '2025-04-10 09:30:00', 'confirmé', '{"participant": true, "talent": "Jonglage", "heure_passage": "18:00"}'),
-- Inscriptions à la chasse aux œufs
(4, 5, '2025-04-07 14:25:00', 'confirmé', '{"nombre_enfants": 2, "ages": [5, 7]}'),
(4, 8, '2025-04-08 10:15:00', 'confirmé', '{"nombre_enfants": 1, "ages": [6]}');

-- Insertion des services
INSERT INTO SERVICE (nom, description, type_service, actif) VALUES
('Alerte Trafic', 'Service d\'alertes en temps réel sur l\'état du trafic routier', 'mobilité', TRUE),
('Signalement Problèmes', 'Signaler des problèmes dans l\'espace public (éclairage défectueux, nids de poule, etc.)', 'citoyenneté', TRUE),
('Réservation Équipements', 'Réserver des équipements municipaux (salles, terrains de sport)', 'loisirs', TRUE),
('Alertes Météo', 'Alertes en cas de conditions météorologiques exceptionnelles', 'sécurité', TRUE),
('Carte Interactive', 'Carte interactive des points d\'intérêt et services de la ville', 'information', TRUE);

SELECT CURRENT_USER();