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
  <script>
    function about(){
      alert("Application de gestion d'incidents\n\nAuteurs :\n  - Nawal Sehaki\n  - Adrien Dauvilaire\n  - Matthias Delamare");
    }
  </script>
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
      </ul>
    <?php if(estTechnicien()){ ?>
      <h3>Statistiques</h3>
      <ul>
        <li><a href="index.php?c=stats&v=par_user">Demandes par utilisateur</a></li>
        <li><a href="index.php?c=stats&v=par_mois">Demandes par mois</a></li>
        <li><a href="index.php?c=stats&v=par_impact">Résolution par importance</a></li>
        <li><a href="index.php?c=stats&v=nb_impact">Demandes par importance</a></li>
      </ul>
			<h3>Administration</h3>
			<ul>
				<li><a href="index.php?c=user&a=list">Liste des utilisateurs</a></li>
				<li><a href="index.php?c=user&a=new">Ajouter un utilisateur</a></li>
			</ul>
    <?php } ?>
      <h3>Divers</h3>
      <ul>
        <li><a href="#" onclick="about()">A propos</a></li>
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
