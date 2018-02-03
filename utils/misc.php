<?php
function getValue($nom, $defaut=null){
  if(isset($_GET[$nom]))
    return $_GET[$nom];
  if(isset($_POST[$nom]))
    return $_POST[$nom];
  return $defaut;
}

function getSessionValue($nom, $defaut=null){
  if(isset($_SESSION[$nom]))
    return $_SESSION[$nom];
  return $defaut;
}

function affichageTemporaire($description){
  echo("TODO : $description");
}

function getLibelleRole(){
  switch(getSessionValue('user_role')){
    case 'tech' :
      return 'technicien';
    case 'user' :
      return 'utilisateur';
    default :
      return '';
  }
}

/**
 * @return booléen vrai si la personne connectée est un technicien
 **/
function estTechnicien(){
  return (getSessionValue('user_role') == 'tech');
}

/**
 * Utilisé dans les vues pour remplir la valeur d'un champ
 * . Si le tableau existe et que
 * la valeur $tableau[$index] existe, renvoie cette valeur, sinon
 * renvoie une chaine vide
 **/
function v($tableau, $index){
  if(!is_null($tableau) && isset($tableau[$index]))
    return $tableau[$index];
  else
    return '';
}

/**
 * Utilisé dans les vues pour sélectionner le bon élément d'un champ select
 * . Si le tableau existe et que
 * la valeur $tableau[$index] vaut $valeur, renvoie
 * le mot selected, sinon vide.
 **/
function s($tableau, $index, $valeur){
  if(v($tableau, $index) == $valeur)
    return 'selected';
  else
    return '';
}

/**
 * Utilisé dans les vues pour passer un champ en lecture seule
 * si la condition n'est pas remplie
 * @return Si la condition est fausse, renvoie 'disabled'
 **/
function d($condition){
  if(!$condition)
    return 'disabled';
  else
    return '';
}

/**
 * Renvoie la date et l'heure formatée à la française
 **/
function formateDateHeure($str_date){
  $jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
  $mois = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
  $d = new DateTime($str_date);
  $j = $jours[(int)$d->format('w')];
  $m = $mois[(int)$d->format('n')];
  return $j . $d->format(' j ') . $m . $d->format(' Y à G:i:s');
}

/**
 * Affiche la variable ou le tableau de manière lisible pour un humain
 * @param $continue boolean indique s'il faut continuer le script.
 * Vaut false par défaut.
 **/
function myDump($variable, $continue=false){
  if(!headers_sent()){
    header('Content-Type: text/html; charset=utf8');
    echo '<!DOCTYPE html><html lang="fr"><body>';
  }
  echo "<xmp>";
  print_r($variable);
  echo "</xmp>";
  $continue || die("--FIN--");
}
?>