<?php
include "utils/db.php";

$sql = array();
$sql['vue'] = 'SELECT * FROM Ticket WHERE tkt_id = :tkt_id';
$sql['modif'] = 'UPDATE Ticket SET 
  tkt_titre =: tkt_titre,
  tkt_description =: tkt_description,
  tkt_solution =: tkt_solution,
  tkt_urgence =: tkt_urgence,
  tkt_impact =: tkt_impact,
  tkt_demandeur =: tkt_demandeur,
  tkt_technicien =: tkt_technicien,
  tkt_etat =: tkt_etat,
  tkt_temps_passe =: tkt_temps_passe,
  tkt_date_demande =: tkt_date_demande,
  tkt_date_pec =: tkt_date_pec,
  tkt_date_solution =: tkt_date_solution,
  WHERE tkt_id = :tkt_id';
$sql['vuser'] = 'SELECT * FROM Ticket WHERE tkt_demandeur = :user_id';
$sql['vatrait'] = 'SELECT * FROM Ticket WHERE tkt_etat = 1';
$sql['vtech'] = 'SELECT * FROM Ticket WHERE tkt_technicien = :user_id';

function executeDemande($index, $params){

}
