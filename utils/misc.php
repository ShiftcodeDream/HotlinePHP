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
  include "view/header.php";
  echo "<div class='erreur'>TODO : $description</div>";
  include "view/footer.php";
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
 * Utilisé dans les vues. Si le tableau existe et que
 * la valeur $tableau[$index], renvoie cette valeur, sinon
 * renvoie une chaine vide
 **/
function v($tableau, $index){
  if(!is_null($tableau) && isset($tableau[$index]))
    return $tableau[$index];
  else
    return '';
}
?>
