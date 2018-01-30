<?php
include "view/TicketView.php";
include "model/TicketModel.php";

$action = getValue('a');

switch($action){
  case 'new' :
    formNewTicket();
    break;
  case 'inscrit' :
    enregistrerTicket();
    break;
  case 'mod' :
    modifierTicket();
    break;
  case 'visu' :
    formShowTicket();
  case 'vatrait' :
    listeTicketsATraiter();
    break;
  case 'vtech' :
    listeTicketsDuTechnicien();
    break;    
  case 'vuser' :
  default :
    listeTicketsUtilisateur();
}

function formNewTicket(){
  ticketVueAfficheForm();
}

function formShowTicket(){
  ticketVueAfficheForm(getTicket(getValue('id')));
}

function enregistrerTicket(){
  affichageTemporaire("Enregistrer le nouveau ticket");
}

function modifierTicket(){
  
}

function listeTicketsATraiter(){
  affichageTemporaire("Lister les tickets à traiter");
}

function listeTicketsDuTechnicien(){
  affichageTemporaire("Lister les tickets du technicien");
}

function listeTicketsUtilisateur(){
  affichageTemporaire("Lister les tickets de l'utilisateur");
}

?>
