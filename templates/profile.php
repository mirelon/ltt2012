<?php

require_once('models/Discount.php');
$user = Session::getLoggedUser();

echo 'Ja som ' . $user->getFullName() . ' <a class="button logout" href="' . $base_url. '?page=logout">Logout</a><br/><br/>';

$discounts = $user->getWonDiscounts();
if (empty($discounts))
{
    echo "Tip: Skús sa niekedy presadiť v dražbe.";
}
else
{
    echo "<h2>Moje zľavy</h2><ul>";
    /* @var $discount Discount */
    foreach ($discounts as $discount)
    {
        echo "<li>" . $discount->title . " (kúpené za " . $discount->getLastBidPrice() . " kreditov)</li>";
    }
    echo "</ul>";
}
?>

