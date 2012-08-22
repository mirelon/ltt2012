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
$logs = Db::fetchAll('SELECT *, COUNT(*) AS access_count FROM user_accesses NATURAL JOIN users GROUP BY user_id ORDER BY access_count DESC;');
echo '<html><head><meta http-equiv="Content-Type" content="text/html" charset="utf-8"></head><body>';
echo '<table><thead><tr><th>Meno</th><th>Priezvisko</th><th>Pocet pristupov</th></tr></thead>';
foreach($logs as $log) {
  echo '<tr>';
  echo '<td>' . $log['first_name'] . '</td><td>' . $log['last_name'] .  '</td><td>' . $log['access_count'] . '</td>';
  echo '</tr>';
}

echo '</table></body></html>';
Db::terminate();



?>
