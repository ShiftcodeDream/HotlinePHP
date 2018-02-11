<?php
include_once("utils/db.php");

function authentifieUtilisateur($user, $password){
  global $db;
  
  $requete = $db->prepare('
    SELECT * FROM Utilisateur
    WHERE lower(usr_login)=:user
    AND usr_passwd=:pass');
  
  $requete->execute(array(
    'user' => strtolower($user),
    'pass' => $password));
  
  $reponse = $requete->fetchAll();
  $requete->closeCursor();
  
  return ($reponse);
}
?>
