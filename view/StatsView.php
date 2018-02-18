<?php

/**
 * Affiche une liste de résultats à un seul niveau
 * $liste array tableau d'enregistrements à afficher
 * $titre string Titre à afficher
 * $lastStat boolean indique s'il s'agit de la dernière statistique de la page
 */
function afficheStatistiqueSimples($liste, $titre, $lastStat = true){
	global $headersOK;
	// Permet d'afficher plusieurs tableaux de statistiques sur la même page
 	include_once "view/header.php";

  echo "<h1>$titre</h1>";
  if(empty($liste)){
    echo "Aucune statistique disponible pour le moment.";
    return;
  }
  // Entete du tableau
  echo "<table class='liste'><thead><tr>";
  $entetes = $liste[0];
  foreach($entetes as $col => $data){
    echo "<th>$col</th>";
  }
  echo "</tr></thead>\n<tbody>\n";
  
  // Données
  foreach($liste as $ligne){
    echo "<tr>";
    foreach($ligne as $col => $data){
      echo "<td>$data</td>";
    }
    echo "</tr>\n";
  }
  echo "</tbody></table>";
  
	if($lastStat){
		include "view/footer.php";
	}
}
?>
