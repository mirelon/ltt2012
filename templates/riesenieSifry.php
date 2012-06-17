<?php
  if(isset($_POST['nazov']) && isset($_POST['riesenie'])) {
    if($_POST['nazov'] == 'trojita' && strtolower($_POST['riesenie']) == 'soschudak') {
      echo 'ok';
    }
  }
?>
