<?php
require_once('models/Discount.php');
?>
<ul>
<?php
foreach (Db::fetchAll('SELECT * FROM discounts;') as $row)
{
    $discount = Discount::fromArray($row);
    ?>
        <li>
            <a href="?page=discount&discount_id=<?php echo $discount->discount_id; ?>">
                <?php echo $discount->title; ?>
            </a>
        </li>
        <?php
    }
    ?>
</ul>