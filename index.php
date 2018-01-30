<?php
include "utils/misc.php";
# Démarrage de la session, récupération des paramètres de session
session_start();
$user_id =   getSessionValue('user_id');
$user_name = getSessionValue('user_name');
$user_role = getSessionValue('user_role');

$erreurs = array(); // Messages d'erreur à afficher 
$champsErreur = array(); // Champ en erreur à mettre en évidence

# L'utilisateur est-il authentifié?
if(!isset($user_id)){
  include "controller/UserAuthentificationController.php";
  exit();
}else{
  # Si l'utilisateur est authentifié, on le dirige vers le controleur qui traitera sa demande
  $controller = $_GET['c'];
  switch($controller){
    case 'user' :
      include "controller/UserController.php";
      break;
    case 'userauth' :
      include "controller/UserAuthentificationController.php";
      break;
    case 'ticket' :
    default :
      include "controller/TicketController.php";
      break;
  }
}

?>
