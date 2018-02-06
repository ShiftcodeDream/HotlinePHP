/* Table des utilisateurs */
CREATE TABLE Utilisateur (
  usr_id INT PRIMARY KEY AUTO_INCREMENT,
  usr_nom VARCHAR(50),
  usr_prenom VARCHAR(50),
  usr_login CHAR(10),
  usr_passwd VARCHAR(50),
  usr_role CHAR(4)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Table des tickets */
CREATE TABLE Ticket (
  tkt_id INT PRIMARY KEY AUTO_INCREMENT,
  tkt_titre VARCHAR(60) DEFAULT ' ',
  tkt_description VARCHAR(500) NOT NULL,
  tkt_solution VARCHAR(500) DEFAULT NULL,
  tkt_urgence INT,
  tkt_impact INT DEFAULT 1,
  tkt_demandeur INT NOT NULL,
  tkt_technicien INT DEFAULT NULL,
  tkt_etat INT,
  tkt_temps_passe INT DEFAULT 0,
  tkt_date_demande DATETIME NOT NULL,
  tkt_date_pec DATETIME DEFAULT NULL,
  tkt_date_solution DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Vue avec noms des acteurs en clair */
CREATE VIEW TicketAll AS
SELECT t.*,
  CONCAT(d.usr_nom, ' ', d.usr_prenom) as tkt_demandeur_nom,
  CONCAT(te.usr_nom, ' ', te.usr_prenom) as tkt_technicien_nom
  FROM Ticket t
  LEFT JOIN Utilisateur d ON d.usr_id = t.tkt_demandeur
  LEFT JOIN Utilisateur te ON te.usr_id = t.tkt_technicien;

INSERT INTO Utilisateur
(usr_nom, usr_prenom, usr_login, usr_passwd, usr_role)
VALUES('La Malice', 'Denis', 'denis', 'ni deux', 'tech');
