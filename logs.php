<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('config.php');
require_once('models/Db.php');

Db::$user = $db_user;
Db::$password = $db_password;
Db::$dbname = $db_name;
Db::$host = $db_host;
Db::init();
$logs = Db::fetchAll('SELECT * FROM user_accesses NATURAL JOIN users ORDER BY timestamp DESC;');
echo '<html><head><meta http-equiv="Content-Type" content="text/html" charset="utf-8"></head><body>';
echo '<table><thead><tr><th>Cas pristupu</th><th>Meno</th><th>Priezvisko</th><th>URL</th></tr></thead>';
foreach($logs as $log) {
  echo '<tr>';
  echo '<td nowrap="nowrap">' . $log['timestamp'] . '</td><td>' . $log['first_name'] . '</td><td>' . $log['last_name'] .  '</td><td>' . $log['url'] . '</td>';
  echo '</tr>';
}

echo '</table></body></html>';
Db::terminate();



?>
