<?php
require_once('models/Discount.php');
if(isset($_GET['action']) && $_GET['action']=='duplicate') {
    $discount = Discount::getById($_GET['discount_id']);
    $discount->getDuplicate()->save();
}
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
            <a href="?page=discount_list&action=duplicate&discount_id=<?php echo $discount->discount_id; ?>">
                --> duplicate
            </a>

        </li>
        <?php
    }
    ?>
</ul>