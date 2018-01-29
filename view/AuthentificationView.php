<?php
include "header.php";
?>
<form name="formAuth" action="index.php?c=userauth&a=logon" method="post">
  <div class="loginform">
    <h1>Veuillez vous authentifier</h1>
    <table border="0">
      <tr><td><label for="login">Identifiant</label></td>
        <td><input type="text" id="login" name="login"></td>
      </tr><tr>
      <td><label for="pass">Mot de passe</label></td>
      <td><input type="password" id="pass" name="pass"></td>
      </tr><tr>
        <td colspan="2"><input type="submit" name="go" value="Entrer"></td>
      </tr>
    </table>
  </div>
</form>
<?php
include "footer.php";
?>
