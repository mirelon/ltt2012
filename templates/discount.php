<?php

require_once('models/Discount.php');

$discount = new Discount();
if(isset($_GET['discount_id'])) {
    $discount = Discount::getById($_GET['discount_id']);
}

if(isset($_POST['title'])) {
    $discount->title = $_POST['title'];
    $discount->asking_price = intval($_POST['asking_price']);
    $discount->price_drop_time = intval($_POST['price_drop_time']);
    $discount->bid_initial_validity = intval($_POST['bid_initial_validity']);
    $discount->bid_validity_decay = intval($_POST['bid_validity_decay']);
    $discount->count = intval($_POST['count']);
    $discount->assignNewOrder();
    $discount->save();
}

?>
<form action="" method="post">
<table cellpadding="0" cellspacing="0">
    <tr><td>Nazov zlavy</td><td><input type="text" name="title" value="<?php echo $discount->getValue("title", "");?>" /></td></tr>
<tr><td>Vyvolavacia cena</td><td><input type="text" name="asking_price" value="<?php echo $discount->getValue("asking_price", "100");?>" /></td></tr>
<tr><td>Kolko sekund, kym klesne vyvolavacia cena</td><td><input type="text" name="price_drop_time" value="<?php echo $discount->getValue("price_drop_time", "0");?>" /></td></tr>
<tr><td>Kolko sekund, kym sa ponuka uzavrie (=bid validity)</td><td><input type="text" name="bid_initial_validity" value="<?php echo $discount->getValue("bid_initial_validity", "86400");?>" /></td></tr>
<tr><td>O kolko sekund klesne bid validity pri kazdom bide</td><td><input type="text" name="bid_validity_decay" value="<?php echo $discount->getValue("bid_validity_decay", "0");?>" /></td></tr>
<tr><td>Pocet</td><td><input type="text" name="count" value="<?php echo $discount->getValue("count", "1");?>" /></td></tr>
<tr><td></td><td><input type="submit" value="Odošli" /></td></tr>
</table>
</form>
