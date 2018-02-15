<?php
include_once "utils/db.php";
/** 
 * Représente la superclasse permettant l'accès aux données
 */

class DatabaseClass{
  protected $requetes = [];
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
