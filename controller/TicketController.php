<?php
include "utils/misc.php";

$action = $_GET['a'];

switch($action){
  case 'new' :
    formNewTicket();
    break;
  case 'inscrit' :
    enregistrerTicket();
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
  include "view/TicketView.php";
}

function formShowTicket(){
  
}

function enregistrerTicket(){
  affichageTemporaire("Enregistrer le nouveau ticket");
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
