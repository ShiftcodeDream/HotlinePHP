<?php
include "utils/db.php";

$RequetesTicket = array(
  'vuser' => 'SELECT * FROM TicketAll WHERE tkt_demandeur = :user_id',
  'vatrait' => 'SELECT * FROM TicketAll WHERE tkt_etat = 0',
  'vtech' => 'SELECT * FROM TicketAll WHERE tkt_technicien = :user_id',
  'un' => 'SELECT * FROM TicketAll WHERE tkt_id = :id',
  'inscrit' => 'INSERT INTO Ticket
    (tkt_titre, tkt_description, tkt_urgence,
    tkt_demandeur, tkt_etat, tkt_date_demande)
    VALUES(:tkt_titre, :tkt_description, :tkt_urgence,
    :tkt_demandeur, 0, sysdate())',
  'pec' => 'UPDATE Ticket SET tkt_etat = 1, tkt_technicien = :technicien
    WHERE tkt_id = :id',
  'modif' => 'UPDATE Ticket SET 
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
    WHERE tkt_id = :tkt_id'
);

function getTicket($ticket_id){
  global $RequetesTicket;
  return dbSelect($RequetesTicket['un'], array('id' => $ticket_id));
}

/**
 * Créée un ticket dans la base de données
 * @param $ticket Tableau associatif contenant les valeurs à insérer
 * @return int le numéro du ticket créé.
 **/
function creeTicket($ticket){
  global $db, $RequetesTicket, $erreurs;
  try{
    $req = $db->prepare($RequetesTicket['inscrit']);
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

function getGenericListeTickets($nom, $params){
  global $erreurs, $RequetesTicket;
  
  if(!array_key_exists($nom, $RequetesTicket))
    return array();
  
  try{
    return dbSelect($RequetesTicket[$nom], $params);
  }catch (PDOException $err){
    $erreurs[] = "Erreur SQL sur la requète $nom : " . $err->getMessage();
    return array();
  }
}