<?php

function escape($string) {
  return mysql_real_escape_string($string);
}

?>
