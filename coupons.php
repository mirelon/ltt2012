<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>';
require_once('config.php');
require_once('models/Db.php');
require_once('models/User.php');
require_once('models/Discount.php');
Db::$user = $db_user;
Db::$password = $db_password;
Db::$dbname = $db_name;
Db::$host = $db_host;
Db::init();

$users = User::fromArrayOfArray(Db::fetchAll('SELECT * FROM users WHERE user_id>12;'));
$i=0;
$html = array(0=>'', 1=>'', 2=>'');
foreach($users as $user) {
  $discounts = $user->getWonDiscounts(isset($_GET['printed']) && $_GET['printed']=='0');
  if(empty($discounts))continue;
  $meno = $user->first_name . " " . $user->last_name;
  foreach($discounts as $discount) {
    $html[$i] .= '<div style="page-break-inside: avoid;border:1px dashed black;padding:5px;display:block;position:relative;float:top;width:95%;font-family:Helvetica;color:#4A5C26;text-align:center;">' . $discount->getCouponHtml($meno) . '</div>';
    if(isset($_GET['print'])) {
      $discount->printed = $_GET['print'];
      $discount->unsetFields(array('bid_id', 'user_id', 'price', 'timestamp', 'extended_validity', 'winning'));
      $discount->save();
    }
    $i=($i+1)%3;
  }  
}
for($j=0;$j<3;$j++) {
  echo '<div style="width:220px; float:left; display:block; ">';
  echo $html[$j];
  echo '</div>';
}
Db::terminate();
?>
