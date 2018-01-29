<?php
function getValue($nom, $defaut=null){
  $v = $_GET[$nom];
  if(isset($v))
    return $v;
  $v = $_POST[$nom];
  if(isset($v))
    return $v;
  return $defaut;
}
?>
