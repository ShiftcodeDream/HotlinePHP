<?php
include "utils/misc.php";

$action = $_GET['a'];

switch($action){
  case 'new' :
    formNewTicket();
    break;
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

include "view/header.php";

echo "<h1>Action demandée : $action</h1>\n";

include "view/footer.php";


function formNewTicket(){
  
}

function listeTicketsATraiter(){
  
}

function listeTicketsDuTechnicien(){
  
}

function listeTicketsUtilisateur(){
  
}

?>
