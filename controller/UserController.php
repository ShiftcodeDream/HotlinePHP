<?php

$action = getValue('a');

switch($action){
  case 'new' :
    formNewUser();
    break;
  case 'inscrit' :
    enregistrerUtilisateur();
    break;
  default :
    header("Location: index.php");
}

function formNewUser(){
  include "view/UserView.php";
}

function enregistrerUtilisateur(){
  affichageTemporaire("Lister les tickets à traiter");
}

?>
