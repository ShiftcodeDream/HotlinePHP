<?php

/**
 * Affiche le formulaire de tickets
 * @param ticket le ticket à afficher. Si null, fournit le formulaire vierge
 * pour créer un nouveau ticket.
 * @param readOnly boolean true si le formulaire doit s'afficher en lecture seuls
 * (pas de modification permise)
 **/
function ticketVueAfficheForm($o = null, $readOnly = false){
  global $erreurs, $champsErreur, $messages;
  include "view/header.php";
  // Définit si le ticket a été créé ou s'il s'agit d'un nouveau ticket
  if(is_null($o)){
    $o = new Ticket();
  }
  $existe = $o->existe();
  $id = $o->getId();
	$ro = $readOnly ? 'readonly' : '';
  
  if($existe){
    $action = 'index.php?c=ticket&id=' . $id;
    $action_texte = 'Enregistrer';
  }else{
    $action = 'index.php?c=ticket&a=inscrit';
    $action_texte = 'Soumettre';
  }
  
  echo "<form name='ticket' action='$action' method='post'>";
  echo '<h1>', $existe ? "Détails du" : "Création d'un" 
    , " ticket soumis par "
    , $existe ? $o->getDemandeurNom() : getSessionValue('user_name', 'inconnu')
    , '</h1>';    
?><input type="hidden" name="a" id="a" value="mod"/>
  <input type="button" value="Retour" onclick="document.location='index.php'">
<?php if(!$readOnly){ ?>
  <input type="submit" value="<?=$action_texte?>">
<?php } ?>
	<script>
		function clore(){
			e = document.getElementById("a");
			if(e !== null){
				e.value = 'clore';
				document.forms[0].submit();
			}
		}
</script>
<?php
  if($existe && estTechnicien()){
    if($o->estSoumis()){
      echo '<input type="button"
      onClick="document.location=\'index.php?c=ticket&a=pec&id=', $id  
      , '\'" value="Prendre en charge"</button">';
    }
    if($o->estPrisEnCharge()){
      echo '<input type="button"
      onClick="clore()" value="Clore le ticket">';
    }
  }
?>
  <table>
    
<?php
  if($existe){
    echo '<tr><td><label>Etat du ticket</label></td><td>',
      ucfirst(Ticket::getLibelleEtat($o->getEtat())),
      '</td></tr>';
  }
?>
    <tr>
      <td><label for="titre" class="obligatoire">Titre</label></td>
      <td><input type="text" id="titre" name="titre" value="<?=$o->getTitre()?>" size="80" <?=$ro?>></td>
    </tr>
    <tr>
      <td><label for="description" class="obligatoire">Description</label></td>
      <td><textarea id="description" name="description" cols="80" rows="10" <?=$ro?>><?=$o->getDescription()?></textarea>
      </td>
    </tr>
<?php
    if($existe){
?>
    <tr>
      <td><label>Demandé par</label></td>
      <td><?=$o->getDemandeurNom()?></td>
    </tr>
<?php } ?>    
<?php
    if($o->estPrisEnCharge() || $o->estResolu()){
?>
    <tr>
      <td><label>Pris en charge par</label></td>
      <td><?=$o->getTechnicienNom()?></td>
    </tr>
<?php }
    $urgence = $o->getUrgence();
    ?>
    <tr>
      <td><label for="urgence">Urgence</label></td>
      <td>
        <select name="urgence" id="urgence" <?=$ro?>>
          <option <?=optionSelection($urgence, 1)?>>pas du tout</option>
          <option <?=optionSelection($urgence, 2)?>>un peu</option>
          <option <?=optionSelection($urgence, 3)?>>moyenne</option>
          <option <?=optionSelection($urgence, 4)?>>élevée</option>
        </select></td>
    </tr>
<?php if($existe && estTechnicien()) {
    $impact = $o->getImpact();
    ?>
    <tr>
      <td><label for="impact">Impact global</label></td>
      <td>
        <select name="impact" id="impact" <?=d(estTechnicien())?> <?=$ro?>>
          <option <?=optionSelection($impact, 1)?>>aucun</option>
          <option <?=optionSelection($impact, 2)?>>faible</option>
          <option <?=optionSelection($impact, 3)?>>moyen</option>
          <option <?=optionSelection($impact, 4)?>>élevé</option>
          <option <?=optionSelection($impact, 5)?>>critique</option>
        </select></td>
    </tr>
<?php } ?>
<?php
  if($o->estPrisEnCharge() || $o->estResolu()){
?>
    <tr>
      <td><label>Date de prise en charge</label></td>
      <td><?=formateDateHeure($o->getDatePec())?></td>
    </tr>
<?php
  }
?>
<?php
  if($o->estResolu()){
?>
    <tr>
      <td><label>Date de résolution</label></td>
      <td><?=formateDateHeure($o->getDateSolution())?></td>
    </tr>
<?php
  }
?>
<?php // Si le ticket n'est pas encore créé, il ne faut pas afficher ces champs
  if($existe){
    // Décide si lees champs suivants sont accessibles en modification
    $modifOK = ($o->getTechnicien() == getSessionValue('user_id') && estTechnicien());
?>
    <tr>
      <td><label for="temps">Temps de résolution</label></td>
      <td>
        <input type="text" id="temps" name="temps" size="5"
          value="<?=$o->getTempsPasse()==0 ? '' : $o->getTempsPasse()?>"
          <?=d($modifOK)?>  <?=$ro?>> (en minutes)
      </td>
    </tr>
    <tr>
      <td><label for="solution">Solution proposée</label></td>
      <td><textarea name="solution" id="solution" cols="80" rows="10" <?=d($modifOK)?>  <?=$ro?>><?=$o->getSolution()?></textarea>
      </td>
    </tr>
<?php    
  }
if($o->existe()){
  echo "<tr><td colspan='2' class='infosTickets'>Numéro de ticket : ", $o->getId(), "</td></tr>";
}else{
  echo "<tr><td colspan='2' class='infosTickets'>Nouveau ticket</td></tr>";
}
?>
</table>
</form>

<?php  
include "view/footer.php";
               
               
               
} // Fin de la fonction ticketVueAfficheForm()

/*
 * Affiche une vue de tickets
 * @param liste tickets à afficher
 */
function afficheListeTicketsATraiter($liste){
  global $erreurs, $messages;
  $action = 'index.php?c=ticket&a=visu&id=';
  
  include "view/header.php";
  echo "<h1>Liste des tickets à traiter</h1>\n";

  if(empty($liste)){
    echo "<p>Liste vide pour le moment.</p>\n";
    return;
  }
  enteteTableau(array('Titre', 'Demandeur', 'Urgence', 'Etat', 'Date de la demande', 'Heure de la demande', 'Technicien'));
  
  foreach($liste as $donnee){
    $uri = $action . $donnee['tkt_id'];
?>
    <tr onclick="document.location='<?=$uri?>'">
      <td><?= $donnee['tkt_titre'] ?></td>
      <td><?= $donnee['tkt_demandeur_nom'] ?></td>
      <td><?= $donnee['urg_nom'] ?></td>
      <td><?= $donnee['etat_nom'] ?></td>
      <td><?= formateDate($donnee['tkt_date_demande']) ?></td>
      <td><?= formateHeure($donnee['tkt_date_demande']) ?></td>
      <td><?= $donnee['tkt_technicien_nom'] ?></td>
    </tr>
<?php
  }
  echo "</tbody></table>";
  include "view/footer.php";
}

/**
 * Affiche les tickets d'un utilisateur avec l'éatt de ses demandes
 **/
function afficheListeTicketsUtilisateur($liste){
  global $erreurs, $messages;
  $action = 'index.php?c=ticket&a=visu&id=';
  
  include "view/header.php";
  echo "<h1>Liste de vos demandes</h1>\n";

  if(empty($liste)){
  echo "<h1>Liste des demandes de " . getSessionValue('user_name') . "</h1>\n";
    return;
  }
	
  enteteTableau(array('Titre', 'Urgence', 'Etat', 'Date de la demande', 'Date de sa prise en charge', 'Date de résolution'));
  
  foreach($liste as $donnee){
    $uri = $action . $donnee['tkt_id'];
?>
    <tr onclick="document.location='<?=$uri?>'">
      <td><?= $donnee['tkt_titre'] ?></td>
      <td><?= $donnee['urg_nom'] ?></td>
			<td><?= $donnee['etat_nom'] ?></td>
      <td><?= formateDateHeure($donnee['tkt_date_demande']) ?></td>
      <td><?= formateDateHeure($donnee['tkt_date_pec']) ?></td>
      <td><?= formateDateHeure($donnee['tkt_date_solution']) ?></td>
    </tr>
<?php
  }
  echo "</tbody></table>";
  include "view/footer.php";	
}

function afficheListeTousTickets($liste){
  global $erreurs, $messages;
  $action = 'index.php?c=ticket&a=visu&id=';
  
  include "view/header.php";
  echo "<h1>Liste de toutes les demandes</h1>\n";

  if(empty($liste)){
    echo "<p>Liste vide pour le moment.</p>\n";
    return;
  }
	
  enteteTableau(array('Titre', 'Demandeur', 'Urgence', 'Etat', 'Technicien affecté',
		'Date de la demande', 'Date de sa prise en charge', 'Date de résolution'));
  
  foreach($liste as $donnee){
    $uri = $action . $donnee['tkt_id'];
?>
    <tr onclick="document.location='<?=$uri?>'">
      <td><?= $donnee['tkt_titre'] ?></td>
      <td><?= $donnee['tkt_demandeur_nom'] ?></td>
      <td><?= $donnee['urg_nom'] ?></td>
			<td><?= $donnee['etat_nom'] ?></td>
      <td><?= $donnee['tkt_technicien_nom'] ?></td>
      <td><?= formateDateHeure($donnee['tkt_date_demande']) ?></td>
      <td><?= formateDateHeure($donnee['tkt_date_pec']) ?></td>
      <td><?= formateDateHeure($donnee['tkt_date_solution']) ?></td>
    </tr>
<?php
  }
  echo "</tbody></table>";
  include "view/footer.php";	
}
/*
 * Affiche une vue de tickets
 * @param liste tickets à afficher
 */
function afficheListeTicketsTechnicien($liste){
  global $erreurs, $messages;
  $action = 'index.php?c=ticket&a=visu&id=';
  
  include "view/header.php";
  echo "<h1>Liste des tickets pris en charge par " . getSessionValue('user_name') . "</h1>\n";

  if(empty($liste)){
    echo "<p>Liste vide pour le moment.</p>\n";
    return;
  }
  enteteTableau(array('Titre', 'Demandeur', 'Urgence', 'Date de la demande', 'Heure de la demande'));
  
  foreach($liste as $donnee){
    $uri = $action . $donnee['tkt_id'];
?>
    <tr onclick="document.location='<?=$uri?>'">
      <td><?= $donnee['tkt_titre'] ?></td>
      <td><?= $donnee['tkt_demandeur_nom'] ?></td>
      <td><?= $donnee['urg_nom'] ?></td>
      <td><?= formateDate($donnee['tkt_date_demande']) ?></td>
      <td><?= formateHeure($donnee['tkt_date_demande']) ?></td>
    </tr>
<?php
  }
  echo "</tbody></table>";
  include "view/footer.php";
}
function enteteTableau($titres){
  echo "<table class='liste avecLiens'><thead><tr>\n";
  foreach($titres as $titre){
		if($titre === 'Titre')
			echo "<th width='25%'>$titre</th>";
		else
    	echo "<th>$titre</th>";
  }
  echo "</tr></thead>\n<tbody>\n";  
}


?>
