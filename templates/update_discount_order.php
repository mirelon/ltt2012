<?php

require_once('models/Discount.php');
if(isset($_POST['discount_order'])) {
    $ids = explode(',', $_POST['discount_order']);
    $old_order = $ids;
    sort($old_order);
    var_dump($ids);
    var_dump($old_order);
    $sql = 'UPDATE discounts SET `order` = CASE `order` ';
    for($i=0;$i<count($ids);$i++) {
        $sql .= sprintf("WHEN %d THEN %d ", $ids[$i], $old_order[$i]);
    }
    $sql .= 'END';
    echo $sql;
    Db::query($sql);
    header('Location: ' . $base_url . '?page=discount_list');
} else echo "discount_order is not set";
?>
