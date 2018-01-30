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

?>
