<?php
if(isset($champsErreur) && count($champsErreur)>0){
?>
<script>
  var liste = ["<?= implode('", "', $champsErreur)?>"];
  document.flagPremier = true;
  for(i=0;i<liste.length;i++){
    evidence(liste[i]);
  }
  function evidence(nom){
    e = document.getElementById(nom);
    if(e){
      e.className += " fielderror";
      if(document.flagPremier){
        e.focus();
        document.flagPremier = false;
      }
    }
  }
</script>
<?php } ?>
</body>
</html>
