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

function vysklonuj($pocet, $vec) {
    $slovnik = array(
        'kredit' => array('kredity', 'kreditov'),
    );
    if(!array_key_exists($vec, $slovnik)) {
        return $pocet . " " . $vec;
    }
    if($pocet == 1) {
        return $pocet . " " . $vec;
    }
    if(is_int($pocet) && $pocet > 1 && $pocet < 5) {
        return $pocet . " " . $slovnik[$vec][0];
    }
    return $pocet . " " . $slovnik[$vec][1];
}

?>
