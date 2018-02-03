<?php
include "view/TicketView.php";
include "model/TicketModel.php";

$action = getValue('a');

switch($action){
  // Afficher le formulaire de saisie
  case 'new' : 
    formNewTicket();
    break;
  // TODO Enregistrer un ticket
  case 'inscrit' :  
    enregistrerTicket();
    break;
  // TODO Prendre en charge un ticket
  case 'pec' : 
    if(estTechnicien()){
      priseEnChargeTicket();
    }
  // TODO Modifier un ticket
  case 'mod' :  
    modifierTicket();
    break;
  // TODO Afficher un ticket
  case 'visu' : 
    formShowTicket();
  // TODO Lister les tickets à traiter (technicien seulement)
  case 'vatrait' :  
    if(estTechnicien()){
      listeTicketsATraiter();
    }else{
      listeTicketsUtilisateur();
    }
    break;
  // TODO Lister les tickets affectés à un technicien
  case 'vtech' : 
    listeTicketsDuTechnicien();
    break;
  // TODO Lister les tickets d'un utilisateur
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

function priseEnChargeTicket(){
  
}
?>
