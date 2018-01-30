<?php
if(count($champsErreur)){
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
