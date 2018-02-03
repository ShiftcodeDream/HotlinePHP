<?php
include "utils/db.php";

$sql = array();
$sql['base'] = "SELECT t.*,
  CONCAT(d.usr_nom, ' ', d.usr_prenom) as tkt_demandeur_nom,
  CONCAT(te.usr_nom, ' ', te.usr_prenom) as tkt_technicien_nom
  FROM Ticket t
  LEFT JOIN Utilisateur d ON d.usr_id = t.tkt_demandeur
  LEFT JOIN Utilisateur te ON te.usr_id = t.tkt_technicien ";

$sql['vue'] = $sql['base'].'WHERE tkt_id = :tkt_id';
$sql['modif'] = 'UPDATE Ticket SET 
  tkt_titre = :tkt_titre,
  tkt_description = :tkt_description,
  tkt_solution = :tkt_solution,
  tkt_urgence = :tkt_urgence,
  tkt_impact = :tkt_impact,
  tkt_demandeur = :tkt_demandeur,
  tkt_technicien = :tkt_technicien,
  tkt_etat = :tkt_etat,
  tkt_temps_passe = :tkt_temps_passe,
  tkt_date_demande = :tkt_date_demande,
  tkt_date_pec = :tkt_date_pec,
  tkt_date_solution = :tkt_date_solution,
  WHERE tkt_id = :tkt_id';
$sql['vuser'] = $sql['base'].'WHERE tkt_demandeur = :user_id';
$sql['vatrait'] = $sql['base'].'WHERE tkt_etat = 1';
$sql['vtech'] = $sql['base'].'WHERE tkt_technicien = :user_id';
$sql['un'] = $sql['base'].'WHERE tkt_id = :id';
$sql['inscrit'] = 'INSERT INTO Ticket
  (tkt_titre, tkt_description, tkt_urgence,
  tkt_demandeur, tkt_etat, tkt_date_demande)
  VALUES(:tkt_titre, :tkt_description, :tkt_urgence,
  :tkt_demandeur, 0, sysdate())';
$sql['pec'] = 'UPDATE Ticket
  SET tkt_etat = 2, tkt_technicien = :technicien
  WHERE tkt_id = :id';

function getTicket($ticket_id){
  return dbSelect($sql['un'], array('id' => $ticket_id));
}

/**
 * Créée un ticket dans la base de données
 * @param $ticket Tableau associatif contenant les valeurs à insérer
 * @return int le numéro du ticket créé.
 **/
function creeTicket($ticket){
  global $db, $sql, $erreurs;
  try{
    $req = $db->prepare($sql['inscrit']);
    $req->execute(array(
      'tkt_titre' => $ticket['tkt_titre'], 
      'tkt_description' => $ticket['tkt_description'],
      'tkt_urgence' => $ticket['tkt_urgence'],
      'tkt_demandeur' => $ticket['tkt_demandeur']
    ));
    return $db->lastInsertId();
  }catch(PDOException $err){
    $erreurs[] = "Erreur SQL : " . $err->getMessage();
    return -1;
  }
}

