<?php
header('Content-Type: text/html; charset=utf8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Hotline en PHP</title>
  <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
<?php if(getSessionValue('user_id') !== null){ ?>
  <nav>
    <h2>Menu</h2>
    <ul>
      <li><a href="index.php?c=ticket&a=new">Nouveau Ticket</a></li>
      <li><a href="index.php?c=ticket&a=vuser">Voir mes Tickets</a></li>
  <?php if(estTechnicien()){ ?>
      <li><a href="index.php?c=ticket&a=vatrait">Tickets à traiter</a></li>
      <li><a href="index.php?c=ticket&a=vtech">Tickets dont j'ai la charge</a></li>
      <li><a href="index.php?c=user&a=new">Nouvel utilisateur</a></li>
  <?php } ?>
      <li><a href="index.php?c=userauth&a=logoff">Déconnexion</a></li>
    </ul>
    <h5>Utilisateur <?=getSessionValue('user_name')?> (<?=getLibelleRole()?>)</h5>
  </nav>
<?php } ?>

<?php
  if(isset($erreurs) && count($erreurs)>0){
    echo '<div class="erreur">';
    foreach($erreurs as $err){
      echo "<p>$err</p>\n";
    }
    echo '</div>';
  }

?>
