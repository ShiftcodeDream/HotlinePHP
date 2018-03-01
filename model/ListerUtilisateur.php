<?php
include_once("utils/db.php");
function ListerUtilisateur(){
  global $db;
  
  
  return dbSelect('
    SELECT usr_nom,usr_prenom,usr_role
	FROM Utilisateur
    order by usr_nom,usr_prenom' ,null);
  
 


}
?>