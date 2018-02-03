<?php


function ticketVueAfficheForm($o=null){
  include "view/header.php";
  if(is_null($o)){
    $action = 'index.php?c=ticket&a=new';
    $action_texte = 'Soumettre';
  }else{
    $action = 'index.php?c=ticket&a=mod';
    $action_texte = 'Enregistrer';
  }
  
  echo "<form name='ticket' action='$action' method='post' onSubmit='return testeValidite()'>";
  echo '<h1>', is_null($o) ? "Création d'un" : 'Détails du'
    , " ticket au nom de "
    , is_null($o) ? getSessionValue('user_name', '') : $o['tkt_nom_demandeur']
    , '</h1>';
  
  // TODO a supprimer (tests only)
  $o = array();
  $o['tkt_id']=15;
?>
  <input type="submit" value="<?=$action_texte?>">
<?php
  if(isset($o) && estTechnicien()){
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
      <td><label for="titre" class="mandatory">Titre</label></td>
      <td><input type="text" id="titre" name="titre" value="<?=v($o,'tkt_titre')?>" size="80"></td>
    </tr>
    <tr>
      <td><label for="description" class="mandatory">Description</label></td>
      <td><textarea id="description" name="description" cols="80" rows="10"><?=v($o,'tkt_description')?></textarea>
      </td>
    </tr>
<?php
    if(v($o,'tkt_demandeur_nom') !== ''){
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
      <td><label for="urgence">Dégré d'urgence</label></td>
      <td>
        <select name="urgence" id="urgence">
          <option value="1" <?=s($o, 'tkt_urgence', 1)?>>pas du tout</option>
          <option value="2" <?=s($o, 'tkt_urgence', 2)?>>peu</option>
          <option value="3" <?=s($o, 'tkt_urgence', 3)?>>moyen</option>
          <option value="4" <?=s($o, 'tkt_urgence', 4)?>>élevé</option>
        </select></td>
    </tr>
<?php if(estTechnicien()) { ?>
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
    <tr>
      <td><label>Date de prise en charge</label></td>
      <td>
<?php
  if(v($o, 'tkt_date_pec') !== ''){
    echo formateDateHeure($o['tkt_date_pec']);
  }
?>
      </td>
    </tr>
    <tr>
      <td><label>Date de résolution</label></td>
      <td>
<?php
  if(v($o, 'tkt_etat') == 2){
    if(v($o, 'tkt_date_solution') !== ''){
      echo formateDateHeure($o['tkt_date_solution']);
    }
  }
?>
      </td>
    </tr>
    <tr>
      <td><label for="temps">Temps de résolution</label></td>
      <td>
        <input type="text" id="temps" name="temps" size="5" value="<?=v($o,'tkt_temps_passe')?>"
          <?=d((v($o, 'tkt_technicien') == getSessionValue('user_id')) && (estTechnicien()))?>> (en minutes)
      </td>
    </tr>
    
<?php // Si le ticket n'est pas encore créé, il ne faut pas afficher ce champ
  if(isset($o)){
?>
    <tr>
      <td><label for="solution">Solution proposée</label></td>
      <td><textarea name="solution" id="solution" cols="80" rows="10" <?=d(estTechnicien())?>><?=v($o, 'solution')?></textarea>
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

?>
