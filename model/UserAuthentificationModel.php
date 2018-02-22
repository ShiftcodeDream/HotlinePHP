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

function createUser($nom, $prenom, $login, $mdp, $role){
      global $db;
// Insertion
$req = $db->prepare('INSERT INTO Utilisateur(usr_nom, usr_prenom, usr_passwd, usr_login, usr_role) VALUES(:nom, :prenom, :mdp, :login, :role)');
return $req->execute(array(
    'nom' => $nom,
    'prenom' => $prenom,
    'mdp' => $mdp,
    'login' => $login,
    'role' => $role));
}
?>