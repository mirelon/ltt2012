<?php
//TODO: init db


$current_page = 'uvod.php';
$base_url = 'http://www.ksp.sk/ltt2012/';
$pages = array(
    'index' => array('script' => 'uvod.php', 'nav' => 'Úvod', 'url' => $base_url),
    'settings' => array('script' => 'settings.php', 'nav' => 'Nastavenia', 'url' => $base_url.'?page=settings')
);
if($_GET['page'] && array_key_exists($_GET['page'], $pages)) {
  $current_page = $pages[$_GET['page']]['script'];
}


require_once('templates/main.php'); 
//TODO: close db
?>
