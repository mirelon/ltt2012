<?php
require_once('models/Discount.php');
if(isset($_GET['action']) && $_GET['action']=='duplicate') {
    $discount = Discount::getById($_GET['discount_id']);
    $discount->getDuplicate()->save();
}
?>
<script type="text/javascript">
    $(function(){$('#sortable').sortable();$('#sortable').disableSelection();});
</script>
<form id="discount_order_form" action="<?php echo $base_url; ?>?page=update_discount_order" method="post">
<h2>Ukončené zľavy</h2>
<ul>
<?php
foreach (Discount::getFinished() as $discount) {
    echo "<li>" . formatSqlTimestamp($discount->timestamp_start) . " - " . $discount->title . "</li>";
}
?>
</ul>
<h2>Aktívne zľavy</h2>
<ul>
<?php
foreach (Discount::getActive() as $discount) {
    echo "<li>" . formatSqlTimestamp($discount->timestamp_start) . " - " . $discount->title . "</li>";
}
?>
</ul>
<h2>Nezačaté zľavy</h2>
Môžeš preusporiadať a editovať:
<ul id="sortable">
<?php
foreach (Discount::getInactive() as $discount)
{
    ?>
        <li order="<?php echo $discount->order; ?>">
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
<input type="hidden" name="discount_order" id="discount_order" value="" />
<input type="submit" value="Updatni poradie" />
</form>

<script type="text/javascript">
    $('form#discount_order_form').submit(function(e){
        console.log(e);
        if($('input#discount_order').val() != '') {
            return true;
        }
        e.preventDefault();
        var idsArray = [];
        $('ul#sortable li').each(function(i,li){
            idsArray.push($(li).attr("order"));
        });
        var ids = idsArray.join(',');
        $('input#discount_order').val(ids);
        $('form#discount_order_form').submit();
    });

</script>