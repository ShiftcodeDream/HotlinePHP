<?php
if(!isset($FrameworkOK)){
  $FrameworkOK = false;
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
        <li><a href="index.php?c=ticket&a=new">Nouvelle demande</a></li>
        <li><a href="index.php?c=ticket&a=vuser">Voir mes demandes</a></li>
    <?php if(estTechnicien()){ ?>
        <li><a href="index.php?c=ticket&a=vatrait">Tickets à traiter</a></li>
        <li><a href="index.php?c=ticket&a=vtech">Tickets dont j'ai la charge</a></li>
        <li><a href="index.php?c=ticket&a=vall">Voir tous les tickets</a></li>
    <?php } ?>
        <li><a href="index.php?c=userauth&a=logoff">Déconnexion</a></li>
      </ul>
      <h5>Utilisateur <?=getSessionValue('user_name')?> (<?=getLibelleRole()?>)</h5>
    </nav>
  <?php }
}
$FrameworkOK = true;

if(!empty($erreurs))
  afficheMsg($erreurs, 'erreur');
if(!empty($messages))
  afficheMsg($messages, 'info');

function afficheMsg($msgs, $classe){
  echo "<div class='$classe'><ul>";
  foreach($msgs as $mes){
    echo "<li>$mes</li>\n";
  }
  echo '</ul></div>';        
}
?>
