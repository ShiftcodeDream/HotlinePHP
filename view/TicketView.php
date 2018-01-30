<?php
include "view/header.php";


function ticketVueAfficheForm($o=null){
  echo '<h1>', is_null($o) ? 'Création' : 'Visualisation'
    , " d'un ticket au nom de "
    , is_null($o) ? getSessionValue('user_name', '') : $o['tkt_nom_demandeur']
    , '</h1>';
  $action = 'index.php?c=ticket&a=' . is_null($o) ? 'new' : 'mod';
?>
<form name="ticket" action="<?=$action?>" method="post">
  <table>
    <tr><td>Titre</td>
      <td><input type="text" id="titre" name="titre" value="<?=v($o,'tkt_titre')?>"></td></tr>
    <tr><td>Description</td>
      <td><textarea id="description" name="description" cols="80" rows="10">
        <?=v($o,'tkt_description')?>
        </textarea></td></tr>
  </table>
<?php  
}

include "view/footer.php";
?>
