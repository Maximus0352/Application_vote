# Application_vote
-- Création de la base de données

-- Table des utilisateurs/électeurs
CREATE TABLE users(
    matricule VARCHAR(20) PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    statut ENUM('actif', 'inactif') DEFAULT 'actif',
    classe VARCHAR(20) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des scrutins
CREATE TABLE scrutins (
    id_scrutin INT AUTO_INCREMENT PRIMARY KEY,
    intitule VARCHAR(100) NOT NULL,
    type_vote ENUM('uninominal', 'plurinominal') NOT NULL,
    classe_concernee VARCHAR(20),
    date_ouverture DATETIME NOT NULL,
    date_cloture DATETIME NOT NULL,
    affichage_resultats BOOLEAN DEFAULT TRUE,
    statut ENUM('en_preparation', 'ouvert', 'cloture', 'depouille') DEFAULT 'en_preparation'
);

-- Table des candidats
CREATE TABLE candidats (
    id_candidat INT AUTO_INCREMENT PRIMARY KEY,
    matricule VARCHAR(20) NOT NULL,
    id_scrutin INT NOT NULL,
    photo VARCHAR(100),
    slogan VARCHAR(200),
    programme TEXT,
    date_depot DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut_validation ENUM('en_attente', 'valide', 'rejete') DEFAULT 'en_attente',
    FOREIGN KEY (matricule) REFERENCES utilisateurs(matricule),
    FOREIGN KEY (id_scrutin) REFERENCES scrutins(id_scrutin)
);

-- Table des votes
CREATE TABLE votes (
    id_vote INT AUTO_INCREMENT PRIMARY KEY,
    matricule VARCHAR(20) NOT NULL,
    id_scrutin INT NOT NULL,
    date_heure DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('valide', 'invalide', 'annule') DEFAULT 'valide',
    FOREIGN KEY (matricule) REFERENCES utilisateurs(matricule),
    FOREIGN KEY (id_scrutin) REFERENCES scrutins(id_scrutin)
);

-- Table des choix de vote (pour garantir l'anonymat)
CREATE TABLE choix_vote (
    id_choix INT AUTO_INCREMENT PRIMARY KEY,
    id_vote INT NOT NULL,
    id_candidat INT NOT NULL,
    FOREIGN KEY (id_vote) REFERENCES votes(id_vote),
    FOREIGN KEY (id_candidat) REFERENCES candidats(id_candidat)
);

-- Table des jetons de vote uniques
CREATE TABLE jetons_vote (
    jeton VARCHAR(64) PRIMARY KEY,
    matricule VARCHAR(20) NOT NULL,
    id_scrutin INT NOT NULL,
    utilise BOOLEAN DEFAULT FALSE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_utilisation DATETIME,
    FOREIGN KEY (matricule) REFERENCES utilisateurs(matricule),
    FOREIGN KEY (id_scrutin) REFERENCES scrutins(id_scrutin)
);
