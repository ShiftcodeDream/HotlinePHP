<?php
include "utils/db.php";
/**
 * Fournit un ensemble d'outils pour effectuer des requètes
 * sur les demandes à des fins statistiques
**/

class Stats{

  // Requetes utilisées par les différentes listes
  protected static $requetes = [
    // nombre de demandes par utilisateur
    'par_user'   => "SELECT tkt_demandeur_nom as Demandeur,
    count(*) as `Nombre de demandes` FROM TicketAll
    GROUP BY Demandeur
    UNION SELECT '  Total', COUNT(*) FROM Ticket",
    // nombre de demandes par mois
    'par_mois'   => "SELECT Annee, mois_nom as Mois, nb as `Nombre de demandes` FROM
      (SELECT DATE_FORMAT(tkt_date_demande,'%Y') as Annee,
        DATE_FORMAT(tkt_date_demande,'%m') as id_mois,
        count(*) as nb
        FROM Ticket
        GROUP BY DATE_FORMAT(tkt_date_demande,'%Y'), DATE_FORMAT(tkt_date_demande,'%m')
      ) AS t
      JOIN LibelleMois m ON m.mois_id = t.id_mois",
    // moyenne du temps de résoluttion par impact
    'par_impact' => "SELECT imp_nom as `Importance (impact)`,
    round(avg(tkt_temps_passe)) as `Temps passé moyen en minutes`
    FROM TicketAll WHERE tkt_etat=2 GROUP BY imp_nom
    UNION SELECT '  Total', round(avg(tkt_temps_passe))
    FROM Ticket WHERE tkt_etat=2",
    // moyenne du temps de résoluttion par urgence
    'par_urgence' => "SELECT urg_nom as `Importance (urgence)`,
    round(avg(tkt_temps_passe)) as `Temps passé moyen en minutes`
    FROM TicketAll WHERE tkt_etat=2 GROUP BY urg_nom
    UNION SELECT '  Total', round(avg(tkt_temps_passe))
    FROM Ticket WHERE tkt_etat=2",
    // nombre de demandes par impact
		'nb_impact'  => 'SELECT imp_nom as `Importance (impact)`, count(*) as `Nombre de demandes`
        FROM TicketAll GROUP BY imp_nom',
    // nombre de demandes par urgence
		'nb_urgence'  => 'SELECT urg_nom as `Importance (urgence)`, count(*) as `Nombre de demandes`
        FROM TicketAll GROUP BY urg_nom',
    // Activité par technicien
    'activ_tech' => "SELECT tkt_technicien_nom as Technicien, Annee, mois_nom as Mois,
    count(*) as `Nombre d'incidents`, sum(tkt_temps_passe) as `Temps passé`
    FROM TicketStats
    WHERE tkt_technicien_nom IS NOT NULL
    GROUP BY Technicien, Annee, Mois"
  ];

  /**
   * Renvoie la liste des tickets, selon le modèle de critères demandé
   * @param nom_liste string nom de la requete à appeler
   * @param params array tableau de paramètres pour la requete sélectionnee
   **/
  public static function getList($nom_liste, $params = null){
    if(!array_key_exists($nom_liste, self::$requetes))
      return array();
    return dbSelect(self::$requetes[$nom_liste], $params);
  }
}
