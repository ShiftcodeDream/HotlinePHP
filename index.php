<?php
include "utils/misc.php";
# Démarrage de la session, récupération des paramètres de session
session_start();

$erreurs = array(); // Messages d'erreur à afficher 
$champsErreur = array(); // Champ en erreur à mettre en évidence
$messages = array(); // Messages d'information (exemple : "utilisateur créé")

# L'utilisateur est-il authentifié?
if(is_null(getSessionValue('user_id'))){
  include "controller/UserAuthentificationController.php";
  exit();
}else{
  # Si l'utilisateur est authentifié, on le dirige vers le controleur qui traitera sa demande
  $controller = getValue('c');
  switch($controller){
    case 'user' :
      include "controller/UserController.php";
      break;
    case 'userauth' :
      include "controller/UserAuthentificationController.php";
      break;
    case 'stats' :
      include "controller/StatsController.php";
      break;
    case 'ticket' :
    default :
      include "controller/TicketController.php";
      break;
  }
}

?>
