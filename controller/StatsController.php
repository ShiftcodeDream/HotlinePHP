<?php
include "view/StatsView.php";
include "model/StatsClass.php";

// Si la personne n'est pas connectée, retour à la page d'authentification
if(is_null(getSessionValue('user_id'))){
  header('Location: index.php');
  exit;
}

$vue = getValue('v');

switch($vue){
  case 'par_user' :
    listeParUser();
    break;
  case 'par_mois' :
    listeParMois();
    break;
  case 'par_impact' :
    listeParImpact();
    break;
  case 'nb_impact' :
    listeNbParImpact();
    break;
  default :
    header('Location: index.php');
}

/**
 * Affiche les statistiques sur le nombre de demandes par utilisateur
 */
function listeParUser(){
   $l = Stats::getList('par_user');
   afficheStatistiqueSimples($l, "Nombre de demandes par utilisateur");
 }

/**
 * Affiche les statistiques sur le nombre de demandes par mois
 **/
function listeParMois(){
   $l = Stats::getList('par_mois');
   afficheStatistiqueSimples($l, "Nombre de demandes par mois");  
}

/**
 * Affiche les statistiques sur la moyenne du temps de résolution
 * par importance
 **/
function listeParImpact(){
   $l = Stats::getList('par_impact', array('champ' => 'tkt_impact'));
   afficheStatistiqueSimples($l, "Moyenne du temps de résolution par importance");  
}

/**
 * Affiche les statistiques sur le nombre de demandes par importance
 **/
function listeNbParImpact(){
   $l = Stats::getList('nb_impact');
   afficheStatistiqueSimples($l, "Nombre de demandes par importance");  
}