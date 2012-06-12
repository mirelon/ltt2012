<?php
/** in case of emergency, use this: **/

//require_once('templates/error.php');
//die;

/*************************************/

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once('config.php');
require_once('models/Session.php');
Db::$user = $db_user;
Db::$password = $db_password;
Db::$dbname = $db_name;
Db::$host = $db_host;
Db::init();
Session::checkLogin();

if(Session::isLoggedIn()) {
    Session::getLoggedUser()->logAccess();
}


$current_page = 'uvod.php';
$pages = array(
    'index' => array('script' => 'uvod.php', 'nav' => 'Úvod', 'url' => $base_url)
);
if(Session::isLoggedIn()) {
  $pages = array_merge($pages, array(
    'drazba' => array('script' => 'drazba.php', 'nav' => 'Dražba', 'url' => $base_url.'?page=drazba'),
    'sifry' => array('script' => 'sifry.php', 'nav' => 'Šifry', 'url' => $base_url.'?page=sifry'),
    'profil' => array('script' => 'profile.php', 'nav' => Session::getLoggedUser()->nick, 'url' => $base_url.'?page=profil'),
    'logout' => array('script' => 'logout.php', 'nav' => null, 'url' => $base_url.'?page=logout'),
  ));
  if(Session::getLoggedUser()->user_id == 3) {
    $pages = array_merge($pages, array(
      'discount' => array('script' => 'discount.php', 'nav' => 'Pridaj zľavu', 'url' => $base_url.'?page=discount'),
      'discount_list' => array('script' => 'discount_list.php', 'nav' => 'Zoznam zliav', 'url' => $base_url.'?page=discount_list'),
      'update_discount_order' => array('script' => 'update_discount_order.php', 'nav' => null, 'url' => $base_url.'?page=update_discount_order'),
    ));

  }
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
