<?php
require_once('config.php');
require_once('models/Db.php');
Db::$user = $db_user;
Db::$password = $db_password;
Db::$dbname = $db_name;
Db::$host = $db_host;
Db::init();

$users = Db::fetchAll('SELECT * FROM users');

foreach($users as $user) {
  echo $user['email'] . " " . $user['password']  . " " .  $user['first_name'] . " " . $user['surname'] . "<br/>";
}
$meno = "Michal Kováč";
$email = "mirelon@gmail.com";
$password = "kjshdfvs";
$html = 'Ahoj ' . $meno . '!<br/>' .
'<br>'.
'Gratulujeme k úspešnému zakúpeniu Super zľavy na LTT!<br/>' .
'Ak sa podobne, ako my, už nevieš dočkať, máš možnosť zakúpiť si ďaľšie zľavy na našom zľavovom portáli: <a href="http://ksp.sk/ltt2012/">ksp.sk/ltt2012</a><br/>' .
'Na portál sa prihlásiš so svojou emailovou adresou ' . $email . ' a so super tajným heslom ' . $password . '.<br/>' .
'Prajem príjemné "nakupovanie". <br/>' .
'<br/>' .
'Miso';
$subject = 'Super zľavy na LTT';
$from = 'miso@ltt.ksp.sk';

mail($email, $subject, $html, 'From: ' . $from . "\r\n" . 'Content-type: text/html; charset=utf-8' . "\r\n");
echo "Sent to " . $email . "<br/>";

Db::terminate();



?>
