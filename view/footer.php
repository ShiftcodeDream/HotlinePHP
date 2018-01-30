<?php
if(isset($champsErreur) && count($champsErreur)>0){
?>
<script>
  var liste = ["<?= implode('", "', $champsErreur)?>"];
  foreach(l in liste){
    evidence(l);
  }
  function evidence(nom){
    e = document.getElementById("nom");
    if(e){
      e.addClass("fielderror");
    }
  }
</script>
<?php } ?>
</body>
</html>
