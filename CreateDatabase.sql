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

/* Table des mois */
CREATE TABLE LibelleMois(
  mois_id INT PRIMARY KEY,
  mois_nom VARCHAR(10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO LibelleMois VALUES
(1,'Janvier'), (2,'Février'), (3,'Mars'), (4,'Avril'), (5,'Mai'), (6,'Juin'),
(7,'Juillet'), (8,'Août'), (9,'Septembre'), (10,'Octobre'), (11,'Novembre'), (12,'Décembre');

/* Vue avec noms des acteurs en clair */
CREATE VIEW TicketAll AS
SELECT t.*,
  CONCAT(d.usr_prenom, ' ', d.usr_nom) as tkt_demandeur_nom,
  CONCAT(te.usr_prenom, ' ', te.usr_nom) as tkt_technicien_nom,
  urg_nom, etat_nom, imp_nom
  FROM Ticket t
  LEFT JOIN Utilisateur d ON d.usr_id = t.tkt_demandeur
  LEFT JOIN Utilisateur te ON te.usr_id = t.tkt_technicien
  LEFT JOIN LibelleUrgence lu ON lu.urg_id = t.tkt_urgence
  LEFT JOIN LibelleEtat et ON et.etat_id = t.tkt_etat
  LEFT JOIN LibelleImpact im ON im.imp_id = t.tkt_impact;

CREATE VIEW TicketStats AS
SELECT *, DATE_FORMAT(tkt_date_demande,'%Y') as Annee, mois_nom as mois
FROM TicketAll
JOIN LibelleMois ON mois_id = DATE_FORMAT(tkt_date_demande,'%m');

INSERT INTO `utilisateur` (`usr_id`, `usr_nom`, `usr_prenom`, `usr_login`, `usr_passwd`, `usr_role`) VALUES
(1, 'La Malice', 'Denis', 'denis', 'ni deux', 'tech'),
(2, 'Lecomte', 'Albert', 'albert', 'bertal', 'util'),
(3, 'Guilbert', 'Yann', 'yann', 'nnya', 'util'),
(4, 'Dalabert', 'Régine', 'regine', 'ginere', 'util'),
(5, 'Pruneau', 'Gilles', 'gilles', 'lesgil', 'tech'),
(6, 'Gubri', 'Christophe', 'christophe', 'tophechris', 'tech');

INSERT INTO `ticket` (`tkt_id`, `tkt_titre`, `tkt_description`, `tkt_solution`, `tkt_urgence`, `tkt_impact`, `tkt_demandeur`, `tkt_technicien`, `tkt_etat`, `tkt_temps_passe`, `tkt_date_demande`, `tkt_date_pec`, `tkt_date_solution`) VALUES
(1, 'Probl&egrave;me de bandoth&egrave;que', 'La bandoth&egrave;que ne fonctionne plus, impossible de sortir la bande...', 'La pi&egrave;ce a &eacute;t&eacute; remplac&eacute;e par un technicien sp&eacute;cialis&eacute;.', 2, 4, 3, 6, 2, 120, '2018-03-05 19:51:35', '2018-02-05 20:37:12', '2018-03-05 20:37:56'),
(2, 'Demande d''achat bloqu&eacute;e', 'Cette DA est bloqu&eacute;e chez Christian :\r\n\r\n&quot;Je ne peux pas valider cette DA car le montant n''est pas inscrit en Euro.\r\n\r\nVoir message ci-dessous:\r\nLa somme des prix unitaires des lignes est sup&eacute;rieure au seuil autoris&eacute;! (10000 &euro;). Veuillez effectuer une demande d''autorisation en cliquant sur le bouton ''Demande accord DG''\r\n\r\nMerci de faire le n&eacute;cessaire pour que je puisse valider.&quot;\r\n\r\nCette DA est de 300 000 yen soit 2622&euro; &lt; 10 000&euro;. Pouvez-vo', 'Le syst&egrave;me choisit l''euro comme devise par d&eacute;faut. N''ayant pas pr&eacute;cis&eacute; la devise utilis&eacute;e dans votre DA, vous comprenez ais&eacute;ment pourquoi cette derni&egrave;re a &eacute;t&eacute; bloqu&eacute;e pour approbation.', 3, 1, 3, 1, 2, 1, '2018-02-05 20:20:23', '2018-03-05 20:58:15', '2018-03-05 21:01:53'),
(3, 'T&eacute;l&eacute;phone au Japon', 'Puis-je utiliser mon smartphone au Japon ? si oui quelles fonctions ? telephone ? messagerie ?', 'Oui, il faut juste activer l''option internationale sur le forfait. Nous avons vu avec votre chef de service, il nous a donn&eacute; l''accord pour activer cette option pour deux mois.', 2, 1, 4, 5, 2, 23, '2018-03-05 20:21:49', '2018-03-05 20:28:40', '2018-03-05 20:32:29'),
(4, 'L''OF ne redescend pas sur l''&eacute;tiqueteuse', 'Bonjour,\r\n\r\nL''OF ne redescend pas sur l''&eacute;tiqueteuse de la ligne annexe.', 'Relance du transfert des OF, v&eacute;rification. OF descendu sur l''&eacute;tiqueteuse.', 4, 4, 4, 6, 2, 3, '2018-02-15 20:23:42', '2018-03-05 21:05:31', '2018-03-05 21:07:10'),
(5, 'Retier TVL des listes de distribution', 'et retirer la visibilit&eacute; en tant que destinataires (droits sur l''objet Notes Profil)', 'Retrait des comptes, mise &agrave; jour de l''ACL des documents du carnet d''adresses pour les groupes concern&eacute;s.', 3, 3, 5, 5, 2, 20, '2018-03-05 20:26:17', '2018-03-05 20:50:02', '2018-03-05 20:50:52'),
(6, 'Probl&egrave;me', 'Jean-Philippe ne peut plus se connecter avec son P.C. Le message suivant s''affiche : &quot;Carte R&eacute;seau D&eacute;sactiv&eacute;e&quot;', 'V&eacute;rification du c&acirc;ble r&eacute;seau, mal clips&eacute; dans la prise murale.', 4, 2, 6, 1, 2, 10, '2018-03-05 20:35:01', '2018-03-05 20:47:03', '2018-03-05 20:48:18'),
(7, 'Probl&egrave;me d''impression', 'Bonjour &agrave; tous,\r\n\r\nBesoin de votre aide, \r\n\r\nV&eacute;ronique ne peux plus imprimer notre tableau de r&eacute;ception et elle en a besoin pour placer la verrerie de la semaine prochaine.\r\n\r\nSalutations \r\nAlbert', 'Impression sur l''imprimante sur service achats.\r\nRemplacement de l''unit&eacute; de fusion sur l''imprimante.', 4, 2, 2, 1, 2, 35, '2018-02-23 20:39:20', '2018-03-05 21:02:05', '2018-03-05 21:03:47'),
(8, 'Mails non re&ccedil;us', 'Apparemment nous avons 10 mails en arriv&eacute;e qui nous ont bien &eacute;t&eacute; envoy&eacute;s que nous ne voyons pas appara&icirc;tre dans la liste ??\r\n\r\nPeux tu svp essayer de voir ce probl&egrave;me.\r\n\r\nMercil', 'Diagnostiques, remise en place de la synchronisation des donn&eacute;es entre ton ordinateur et le serveur.\r\nProbl&egrave;me r&eacute;solu.', 2, 1, 2, 5, 2, 20, '2018-03-05 20:41:18', '2018-03-05 20:48:41', '2018-03-05 20:49:54'),
(9, 'Acc&egrave;s &agrave; la cuverie', 'Bonjour\r\n\r\nDepuis ce matin il est impossible d''ouvrir la cuverie nous avons essay&eacute; plusieurs fois et nous en avons besoin pour pouvoir fabriquer ce matin.\r\nMerci par avance', 'Le serveur &eacute;tait toujours en cours de sauvegarde, rejetant les tentatives de connexion. Probl&egrave;me r&eacute;solu pour ce matin, nous lancerons une nouvelle sauvegarde manuelle pendant l''heure du midi. Merci de nous pr&eacute;venir s''il n''est pas possible de couper le serveur ce midi.', 4, 1, 4, 5, 2, 15, '2018-03-05 20:42:56', '2018-03-05 20:52:18', '2018-03-05 20:57:44'),
(10, 'Machine virtuelle salle de formation', 'Bonjour &agrave; tous,\r\n\r\nSerait-il possible d''avoir une machine virtuelle en salle de formation (au rez de chauss&eacute;e) pour la r&eacute;union 5S de lundi prochain?\r\n\r\nMerci', 'Mise en place du mat&eacute;riel dans la salle de formation ce vendredi pour la r&eacute;union de lundi.\r\nBonn weekend ;-)', 3, 1, 4, 1, 2, 15, '2018-03-05 20:45:09', '2018-03-05 21:03:56', '2018-03-05 21:05:15'),
(11, 'Cl&ocirc;ture d''un ordre de distribution', 'Bonsoir Nicole, \r\n\r\nM. Jancel a bien re&ccedil;u son OD 0000518769 aujourd''hui comme pr&eacute;vu. \r\nPourrais-tu avoir la gentillesse de le cl&ocirc;turer dans l''ERP s''il te pla&icirc;t ? \r\nJe fais un HELPME en parall&egrave;le, au cas o&ugrave; l''aide de l''informatique serait n&eacute;cessaire. \r\n\r\nUn grand merci pour ton aide et bon WE avec un peu d''avance. \r\n\r\nCordialement,\r\nJacquelyne', NULL, 1, 1, 4, NULL, 0, 0, '2018-03-05 21:15:57', NULL, NULL),
(12, 'Droits sur FH', 'Merci de me redonner les droits sur les FH BAT Ext 6547 et 6105', NULL, 3, 1, 2, NULL, 0, 0, '2018-03-05 21:17:49', NULL, NULL),
(13, 'Probl&egrave;me de stabilit&eacute; de wifi', 'Le wifi se d&eacute;connecte sans cesse sur le poste d''Astrid et le mien dans mon bureau\r\n\r\nQuid du fonctionnement ailleurs?', NULL, 2, 1, 2, NULL, 0, 0, '2018-03-05 21:21:54', NULL, NULL);
