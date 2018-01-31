<?php
include "view/header.php";


function ticketVueAfficheForm($o=null){
  $action = 'index.php?c=ticket&a=' . is_null($o) ? 'new' : 'mod';
  echo "<form name='ticket' action='$action' method='post'>";
  echo '<h1>', is_null($o) ? "Création d'un" : 'Détails du'
    , " ticket au nom de "
    , is_null($o) ? getSessionValue('user_name', '') : $o['tkt_nom_demandeur']
    , '</h1>';
  
  $o = array();
  $o['tkt_technicien'] = 1;
  $o['tkt_technicien_nom'] = 'simple test';
?>
  <table>
<?php if(v($o, 'tkt_etat') !== ''){
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
        <select name="impact" id="impact">
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
<?php
  if((v($o, 'tkt_technicien') == getSessionValue('user_id')) && (estTechnicien()) ){
    echo '<input type="text" id="temps" name="temps" value="'
      , v($o,'tkt_temps_passe')
      , '" size="5"> (en minutes)</td>';
  }else{
    if(v($o,'tkt_temps_passe') !== '')
      echo v($o,'tkt_temps_passe'), ' minutes';
  }
?>
    </tr>
    

    <tr><td colspan="2"><h1 style="color:red">TODO : Coder la solution apportée</h1></td></tr>
    
  </table>
<?php  
}

include "view/footer.php";
?>
