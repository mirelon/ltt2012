<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once('models/Session.php');
Db::init();
Session::checkLogin();

$current_page = 'uvod.php';
$base_url = 'http://people.ksp.sk/~miso/ltt2012/';
$pages = array(
    'index' => array('script' => 'uvod.php', 'nav' => 'Úvod', 'url' => $base_url)
);
if(Session::isLoggedIn()) {
  $pages = array_merge($pages, array(
    'sifry' => array('script' => 'sifry.php', 'nav' => 'Šifry', 'url' => $base_url.'?page=sifry'),
    'logout' => array('script' => 'logout.php', 'nav' => 'Logout', 'url' => $base_url.'?page=logout'),
  ));
} else {
  $pages = array_merge($pages, array(
    'login' => array('script' => 'login.php', 'nav' => 'Login', 'url' => $base_url.'?page=login')
  ));
}
//    'settings' => array('script' => 'settings.php', 'nav' => 'Nastavenia', 'url' => $base_url.'?page=settings')

if(isset($_GET['page']) && array_key_exists($_GET['page'], $pages)) {
  $current_page = $pages[$_GET['page']]['script'];
}

require_once('templates/main.php'); 

Db::terminate();
?>
