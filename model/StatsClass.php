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
    'par_user'   => 'SELECT tkt_demandeur_nom as Demandeur,
    count(*) as `Nombre de demandes` FROM TicketAll',
    // nombre de demandes par mois
    'par_mois'   => "SELECT Annee, mois_nom as Mois, nb as `Nombre de demandes` FROM
      (SELECT DATE_FORMAT(tkt_date_demande,'%Y') as Annee,
        DATE_FORMAT(tkt_date_demande,'%m') as id_mois,
        count(*) as nb
        FROM Ticket
        GROUP BY DATE_FORMAT(tkt_date_demande,'%Y'), DATE_FORMAT(tkt_date_demande,'%m')
      ) AS t
      JOIN LibelleMois m ON m.mois_id = t.id_mois",
    // moyenne du temps de résoluttion par importance
    'par_impact' => 'SELECT imp_nom as `Importance (impact)`,
    avg(tkt_temps_passe) as `Temps passé moyen en minutes`
    FROM TicketAll WHERE tkt_etat=2 GROUP BY imp_nom',
    // nombre de demandes par importance
		'nb_impact'  => 'SELECT imp_nom as `Importance (impact)`, count(*) as `Nombre de demandes` FROM TicketAll GROUP BY imp_nom'
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
