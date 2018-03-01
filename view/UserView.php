<?php
function afficheformulaire($nom='', $prenom='', $login='', $mdp='', $role=''){
    global $erreurs, $champsErreur;
    include "view/header.php";

   

?>
<form method="post" action="index.php?c=user&a=inscrit">
<table>
    <tr>
      <td><label for="nom" class="obligatoire">Nom</label></td>
      <td><input type="text" id="nom" name="nom" size="20" value="<?=$nom?>"></td>
    </tr>
    <tr>
      <td><label for="prenom" class="obligatoire" >Prénom</label></td>
      <td><input type="text" id="prenom" name="prenom" size="20" value="<?=$prenom?>"></td>
    </tr>
     <tr>
      <td><label for="login" class="obligatoire" >Login</label></td>
      <td><input type="text" id="login" name="login" size="20" value="<?=$login?>"></td>
    </tr>
    <tr>
      <td><label for="mdp" class="obligatoire">Mot de passe</label></td>
      <td><input type="password" id="mdp" name="mdp" size="20" value="<?=$mdp?>"></td>
    </tr>
    <tr>
      <td><label for="role" class="obligatoire">Role</label></td>
      <td><select id="role" name="role">
          <option value="util">Utilisateur</option>
          <option value="tech">Technicien</option>
          </select>
      </td>
    </tr>
    <tr>
        <td></td>
      <td><input type="submit" value="Envoyer"></td>
    </tr>
</table>
</form>
<?php
    include "view/footer.php";

}

function afficherlisteUtilisateur ($liste){
	include "view/header.php";
	echo "<h1>Liste des utilisateurs</h1>";
echo '<table class="liste">';
echo "<thead><tr><th>Nom</th><th>Prénom</th><th>Role</th></tr></thead>";

 foreach($liste as $donnee){
   
?>
    <tr>
      <td><?= $donnee['usr_nom'] ?></td>
      <td><?= $donnee['usr_prenom'] ?></td>
      <td><?= $donnee['usr_role'] ?></td>
      
  
    </tr>
<?php
  }
echo '</table>';
include "view/footer.php";
}
?>