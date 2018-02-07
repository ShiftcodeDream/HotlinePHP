<?php
include "config/credential.php";

$rs = 'mysql:host=' . _DB_SERVER_ . ';dbname=' . _DB_DATABASE_ . ';charset=utf8';

try {
  $db = new PDO($rs, _DB_USER_ , _DB_PASSWORD_);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die($e->getMessage());
}

/**
 * Prépare et exécute une requete SELECT
 * @param $sql string la requete préparée
 * @param $params array tableau associatif
 * des paramètres à passer à la requête
 * @return array tableau de tableaux d'enregistrements
 * Renvoie null s'il n'y a pas de valeurs à renvoyer
 * @see fetchAll
 **/
function dbSelect($sql, $params){
  global $db;

	$requete = $db->prepare($sql);
  $requete->execute($params);
  $reponse = $requete->fetchAll();
  $requete->closeCursor();
  
  return empty($reponse) ? null : $reponse;
}

/** Prépare et exécute une requete de mise à jour
 * ou de suppression d'enregistrement
 * @param $sql string la requete à exécuter
 * @param $params array le tableau associatif des paramètres
 * @return int nombre de lignes affectées par la modification
 **/
function dbExecute($sql, $params){
	global $db;
	
	$requete = $db->prepare($sql);
	$requete->execute($params);
	
	return $requete->rowCount();
}

/**
 * Prépare et exécute une requete SELECT
 * mais ne renvoie que le premier élément du résultat.
 * @param $sql string la requete préparée
 * @param $params array tableau associatif
 * des paramètres à passer à la requête
 * @return array tableau de tableaux d'enregistrements
 * Renvoie null s'il n'y a pas de valeurs à renvoyer
 * @see fetchAll
 **/

function dbGetOne($sql, $params){
  global $db;

	$requete = $db->prepare($sql);
  $requete->execute($params);
  $reponse = $requete->fetch();
  $requete->closeCursor();
  
  return empty($reponse) ? null : $reponse;
}
?>
