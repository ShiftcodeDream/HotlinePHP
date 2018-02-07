<?php


function ticketVueAfficheForm($o=null){
  global $erreurs, $champsErreur, $messages;
// TODO a supprimer (tests only)
//  $o = array();
//  $o['tkt_id']=15;
  include "view/header.php";
  // Définit si le ticket a été créé ou s'il s'agit d'un nouveau ticket
  $existe = isset($o['tkt_id']);
  
  if($existe){
    $action = 'index.php?c=ticket&a=mod&id=' . $o['tkt_id'];
    $action_texte = 'Enregistrer';
  }else{
    $action = 'index.php?c=ticket&a=inscrit';
    $action_texte = 'Soumettre';
  }
  
  echo "<form name='ticket' action='$action' method='post' onSubmit='return testeValidite()'>";
  echo '<h1>', is_null($o) ? "Création d'un" : 'Détails du'
    , " ticket au nom de "
    , $existe ? $o['tkt_demandeur_nom'] : getSessionValue('user_name', '')
    , '</h1>';
  
  
?>
  <input type="submit" value="<?=$action_texte?>">
<?php
  if($existe && estTechnicien() && $o['tkt_etat']==0){
    echo '<button type="button"
    onClick="document.location=\'index.php?c=ticket&a=pec&id='
    , $o['tkt_id']  
    , '\'">Prendre en charge</button>';
  }
?>
  <table>
    
<?php
  if(v($o, 'tkt_etat') !== ''){
    echo '<tr><td><label>Etat du ticket</label></td><td>';
    switch($o['tkt_etat']){
      case '0' : 
        echo 'Soumis';
        break;
      case '1' :
        echo 'Pris en charge';
        break;
      case '2' :
        echo 'Résolu';
    }
    echo '</td></tr>';
  }
?>
    <tr>
      <td><label for="titre" class="obligatoire">Titre</label></td>
      <td><input type="text" id="titre" name="titre" value="<?=v($o,'tkt_titre')?>" size="80"></td>
    </tr>
    <tr>
      <td><label for="description" class="obligatoire">Description</label></td>
      <td><textarea id="description" name="description" cols="80" rows="10"><?=v($o,'tkt_description')?></textarea>
      </td>
    </tr>
<?php
    if($existe){
?>
    <tr>
      <td><label>Demandé par</label></td>
      <td><?=v($o,'tkt_demandeur_nom')?></td>
    </tr>
<?php } ?>    
<?php
    if(v($o,'tkt_technicien_nom') !== ''){
?>
    <tr>
      <td><label>Pris en charge par</label></td>
      <td><?=v($o,'tkt_technicien_nom')?></td>
    </tr>
<?php } ?>    
    <tr>
      <td><label for="urgence">Urgence</label></td>
      <td>
        <select name="urgence" id="urgence">
          <option value="1" <?=s($o, 'tkt_urgence', 1)?>>pas du tout</option>
          <option value="2" <?=s($o, 'tkt_urgence', 2)?>>peu</option>
          <option value="3" <?=s($o, 'tkt_urgence', 3)?>>moyenne</option>
          <option value="4" <?=s($o, 'tkt_urgence', 4)?>>élevée</option>
        </select></td>
    </tr>
<?php if($existe && estTechnicien()) { ?>
    <tr>
      <td><label for="impact">Impact global</label></td>
      <td>
        <select name="impact" id="impact" <?=d(estTechnicien())?>>
          <option value="1" <?=s($o, 'tkt_impact', 1)?>>aucun</option>
          <option value="2" <?=s($o, 'tkt_impact', 2)?>>faible</option>
          <option value="3" <?=s($o, 'tkt_impact', 3)?>>moyen</option>
          <option value="4" <?=s($o, 'tkt_impact', 4)?>>élevé</option>
          <option value="5" <?=s($o, 'tkt_impact', 5)?>>critique</option>
        </select></td>
    </tr>
<?php } ?>
<?php
  if(v($o, 'tkt_etat') !== 0){
?>
    <tr>
      <td><label>Date de prise en charge</label></td>
      <td><?=formateDateHeure($o['tkt_date_pec'])?></td>
    </tr>
<?php
  }
?>
<?php
  if(v($o, 'tkt_etat') == 2){
    if(v($o, 'tkt_date_solution') !== ''){
?>
    <tr>
      <td><label>Date de résolution</label></td>
      <td><?=formateDateHeure($o['tkt_date_solution'])?></td>
    </tr>
<?php
    }
  }
?>
<?php // Si le ticket n'est pas encore créé, il ne faut pas afficher ces champs
  if($existe){
?>
    <tr>
      <td><label for="temps">Temps de résolution</label></td>
      <td>
        <input type="text" id="temps" name="temps" size="5"
          value="<?=v($o,'tkt_temps_passe')?>"
          <?=d((v($o, 'tkt_technicien') == getSessionValue('user_id')) && (estTechnicien()))?>> (en minutes)
      </td>
    </tr>
    <tr>
      <td><label for="solution">Solution proposée</label></td>
      <td><textarea name="solution" id="solution" cols="80" rows="10" <?=d((v($o, 'tkt_technicien') == getSessionValue('user_id')) && (estTechnicien()))?>><?=v($o, 'solution')?></textarea>
      </td>
    </tr>
<?php    
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
 * @param action string uri à appeler lorsque la personne clique sur le lien. Est rajouté à la fin de l'uri l'identifiant du ticket
 * @param champs array table associative contenant en clé le nom du champ à afficher et en valeur le libellé de la colonne.
 * @param champIndex string le nom du champ qui contient l'identifiant unique du ticket.
 * @param titre string titre de la vue à afficher
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
  enteteTableau(array('Titre', 'Demandeur', 'Date de la demande', 'Heure de la demande'));
  
  foreach($liste as $donnee){
    $uri = $action . $donnee['tkt_id'];
?>
    <tr onclick="document.location='<?=$uri?>'">
      <td><?=$donnee['tkt_titre']?></td>
      <td><?=$donnee['tkt_demandeur_nom']?></td>
      <td><?=formateDate($donnee['tkt_date_demande'])?></td>
      <td><?=formateHeure($donnee['tkt_date_demande'])?></td>
    </tr>
<?php
  }
  echo "</tbody></table>";
  include "view/footer.php";
}

function enteteTableau($titres){
  echo "<table class='liste'><thead><tr>\n";
  foreach($titres as $titre){
    echo "<th>$titre</th>";
  }
  echo "</tr></thead>\n<tbody>\n";  
}


?>
