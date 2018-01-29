<?php
include "config/credential.php";

$rs = 'mysql:host=' . _DB_SERVER_ . ';dbname=' . _DB_DATABASE_ . ';charset=utf8';

try {
  $db = new PDO($rs, _DB_USER_ , _DB_PASSWORD_);
} catch(PDOException $e) {
  die($e->getMessage());
}
?>
