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

$current_page_index = 'index';
$current_page_script = 'uvod.php';
$pages = array(
    'index' => array('script' => 'uvod.php', 'nav' => 'Úvod', 'url' => $base_url, 'layout'=>'main.php')
);
if(Session::isLoggedIn()) {
  $pages = array_merge($pages, array(
    'drazba' => array('script' => 'drazba.php', 'nav' => 'Dražba', 'url' => $base_url.'?page=drazba', 'layout'=>'main.php'),
    'sifry' => array('script' => 'sifry.php', 'nav' => 'Šifry', 'url' => $base_url.'?page=sifry', 'layout'=>'main.php'),
    'profil' => array('script' => 'profile.php', 'nav' => Session::getLoggedUser()->getFullName(), 'url' => $base_url.'?page=profil', 'layout'=>'main.php'),
    'logout' => array('script' => 'logout.php', 'nav' => null, 'url' => $base_url.'?page=logout', 'layout'=>'main.php'),
    'riesenieSifry' => array('script' => 'riesenieSifry.php', 'nav' => null, 'url' => $base_url.'?page=riesenieSifry', 'layout'=>null),
  ));
  if(Session::getLoggedUser()->user_id == 3) {
    $pages = array_merge($pages, array(
      'discount' => array('script' => 'discount.php', 'nav' => 'Pridaj zľavu', 'url' => $base_url.'?page=discount', 'layout'=>'main.php'),
      'discount_list' => array('script' => 'discount_list.php', 'nav' => 'Zoznam zliav', 'url' => $base_url.'?page=discount_list', 'layout'=>'main.php'),
      'update_discount_order' => array('script' => 'update_discount_order.php', 'nav' => null, 'url' => $base_url.'?page=update_discount_order', 'layout'=>'main.php'),
    ));

  }
} else {
  $pages = array_merge($pages, array(
    'login' => array('script' => 'login.php', 'nav' => 'Login', 'url' => $base_url.'?page=login', 'layout'=>'main.php')
  ));
}
//    'settings' => array('script' => 'settings.php', 'nav' => 'Nastavenia', 'url' => $base_url.'?page=settings')
if(isset($_GET['page']) && array_key_exists($_GET['page'], $pages)) {
  $current_page_index = $_GET['page'];
  $current_page_script = $pages[$_GET['page']]['script'];
}
if(array_key_exists('layout', $pages[$current_page_index]) && !is_null($pages[$current_page_index]['layout'])) {
  require_once('templates/' . $pages[$current_page_index]['layout']);
} else {
  require_once('templates/' . $current_page_script);
}
Db::terminate();
?>
