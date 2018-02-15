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
    'par_user'   => 'SELECT tkt_demandeur_nom as Demandeur, count(*) as `Nombre de demandes` FROM TicketAll',
    // nombre de demandes par mois
    'par_mois'   => 'TODO',
    // moyenne du temps de résoluttion par importance
    'par_impact' => 'SELECT :champ, avg(tkt_temps_passe) as `Temps passé moyen` FROM Ticket WHERE tkt_etat=2 GROUP BY :champ',
    // nombre de demandes par importance
		'nb_impact'  => 'SELECT :champ, count(*) FROM Ticket GROUP BY :champ'
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