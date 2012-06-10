<?php

function escape($string) {
  return mysql_real_escape_string($string);
}

function formatTimestamp($timestamp) {
    return date("j.n. H:i:s", $timestamp);
}

function formatSqlTimestamp($sql_timestamp) {
    return formatTimestamp(strtotime($sql_timestamp));
}

function NOW() {
    return date('Y-m-d G:i:s');
}

?>
