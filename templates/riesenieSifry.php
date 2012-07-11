<?php
  sleep(10);
  if(isset($_POST['nazov']) && isset($_POST['riesenie'])) {
    if($_POST['nazov'] == 'trojita' && strtolower($_POST['riesenie']) == 'soschudak') {
      Session::getLoggedUser()->logSolved()->save();
      echo 'ok';
    }
  }
?>
