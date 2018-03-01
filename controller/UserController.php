<?php
include "model/UserAuthentificationModel.php";
include "view/UserView.php";
include "model/ListerUtilisateur.php";

$action = getValue('a');
switch($action){
  case 'new' :
    formNewUser();
    break;
  case 'inscrit' :
    enregistrerUtilisateur();
    break;
	case 'list' :
		controleurlisterUtilisateur();
		break;
  default :
    header("Location: index.php");
}

function formNewUser(){
    afficheformulaire();
    
}

function controleurlisterUtilisateur(){
  global $erreurs, $champsErreur,$messages;
	/*include("utils/db.php");*/
 $liste= ListerUtilisateur();
  afficherlisteUtilisateur ($liste);
	/*include "model/ListerUtilisateur.php";*/
	include "view/footer.php";
}


function enregistrerUtilisateur(){
  global $erreurs, $champsErreur,$messages;
    $nom = getValue("nom");
    $prenom = getValue("prenom");
    $login = getValue("login");
    $mdp = getValue("mdp");
    $role = getValue("role");
    if (empty($nom)){
        $erreurs[] = "Vous n'avez pas renseigné le nom";
        $champsErreur[] = "nom";
    }
     if (empty($prenom)){
        $erreurs[] = "Vous n'avez pas renseigné le prenom";
        $champsErreur[] = "prenom";
    }
     if (empty($login)){
        $erreurs[] = "Vous n'avez pas renseigné le login";
        $champsErreur[] = "login";
    }
     if (empty($mdp)){
        $erreurs[] = "Vous n'avez pas renseigné le mot de passe";
        $champsErreur[] = "mdp";
    }
     if (empty($role)){
        $erreurs[] = "Vous n'avez pas renseigné le role";
        $champsErreur[] = "role";
    }
    if (empty($erreurs)){
        if (createUser($nom, $prenom, $login, $mdp, $role)){
            $messages[] = "L'utilisateur $prenom $nom a été créé";
            controleurlisterUtilisateur();
        }
        else
        {
            afficheformulaire($nom, $prenom, $login, $mdp, $role);
        }
    }
    else
    {
        afficheformulaire($nom, $prenom, $login, $mdp, $role);
    } 
        
}


?>
