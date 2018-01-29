<?php
include "utils/misc.php";
include "model/UserAuthentificationModel.php";

$action = $_GET['a'];

switch($action){
  case 'logon' :
    login();
    break;
  case 'logoff' :
    logoff();
    break;
  default :
    showLogonForm();
}

function login(){
  global $erreurs;
  unset($_SESSION['user_id']);
  unset($_SESSION['user_name']);
  unset($_SESSION['user_role']);
  
  $usr = getValue('login');
  $mdp = getValue('pass');
  
  if(!isset($usr)){
    $erreurs[] = "Vous n'avez pas renseigné votre identifiant";
  }elseif(!isset($mdp)){
    $erreurs[] = "Vous n'avez pas saisi votre mot de passe";
  }else{
    $reponse = authentifieUtilisateur($usr, $mdp);
    if(count($reponse)){
      $_SESSION['user_id'] = $reponse[0]['usr_id'];
      $_SESSION['user_name'] = $reponse[0]['usr_prenom'] . ' ' . $reponse[0]['usr_nom'];
      $_SESSION['user_role'] = $reponse[0]['usr_role'];
      // Si technicien, on le dirige vers la liste des tickets à traiter
      // Sinon, on redirige vers la liste des tickets de l'utilisateur
      if($_SESSION['user_role'] === 'tech')
        $action = 'vatrait';
      else
        $action = 'vuser';
      header("Location: index.php?c=ticket&a=$action");
      exit();
    }else{
      $erreurs[] = "Mauvais identifiant ou mot de passe";
    }
  }
  // Si on arrive ici, c'est que l'authentification a échoué, d'une manière ou d'une autre.
  // On présente à nouveau le formulaire, éventuellement affublé de quelques commentaires.
  showLogonForm();
}

function showLogonForm(){
  global $erreurs;
  include "view/AuthentificationView.php";
  exit();
}

function logoff(){
  session_destroy();
  header('Location: index.php');
}

include "utils/db.php";

?>
