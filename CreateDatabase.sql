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

/* Table des libellé< d'état */
CREATE TABLE LibelleEtat(
  etat_id INT PRIMARY KEY,
  etat_nom VARCHAR(15)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO LibelleEtat VALUES
  (0, 'Soumis'), (1,'Pris en charge'), (2, 'Résolu');

/* Tables des libellés d'urgence */
CREATE TABLE LibelleUrgence(
  urg_id INT PRIMARY KEY,
  urg_nom VARCHAR(15)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO LibelleUrgence VALUES
  (1, 'Pas du tout'), (2,'Un peu'), (3, 'Moyenne'), (4,'élevée');

/* Table des libellés d'impact */
CREATE TABLE LibelleImpact(
  imp_id INT PRIMARY KEY,
  imp_nom VARCHAR(15)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO LibelleImpact VALUES
  (1, 'Aucun'), (2,'Faible'), (3, 'Moyen'), (4,'élevé'), (5,'Critique');

/* Vue avec noms des acteurs en clair */
CREATE VIEW TicketAll AS
SELECT t.*,
  CONCAT(d.usr_prenom, ' ', d.usr_nom) as tkt_demandeur_nom,
  CONCAT(te.usr_prenom, ' ', te.usr_nom) as tkt_technicien_nom,
  urg_nom, etat_nom, imp_nom
  FROM Ticket t
  JOIN Utilisateur d ON d.usr_id = t.tkt_demandeur
  JOIN Utilisateur te ON te.usr_id = t.tkt_technicien
  JOIN LibelleUrgence lu ON lu.urg_id = t.tkt_urgence
  JOIN LibelleEtat et ON et.etat_id = t.tkt_etat
  JOIN LibelleImpact im ON im.imp_id = t.tkt_impact

INSERT INTO Utilisateur
(usr_nom, usr_prenom, usr_login, usr_passwd, usr_role)
VALUES('La Malice', 'Denis', 'denis', 'ni deux', 'tech');
