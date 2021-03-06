<?php
require_once('models/Discount.php');
Discount::processExpired();
Discount::processUpdateActiveCount();
if (isset($_GET['action']))
{
    if ($_GET['action'] == 'duplicate')
    {
        $discount = Discount::getById($_GET['discount_id']);
        $discount->getDuplicate()->save();
    }
    if ($_GET['action'] == 'delete')
    {
        $discount = Discount::getById($_GET['discount_id']);
        $discount->delete();
    }
}
?>
<script type="text/javascript">
    $(function(){$('#sortable').sortable();$('#sortable').disableSelection();});
</script>
<form id="discount_order_form" action="<?php echo $base_url; ?>?page=update_discount_order" method="post">
    <h2>Ukončené zľavy</h2>
    <ul>
        <?php
        foreach (Discount::getFinished() as $discount)
        {
            echo "<li>"
            . formatSqlTimestamp($discount->timestamp_start)
            . " - "
            . $discount->title
            . '<a class="button duplicate" href="?page=discount_list&action=duplicate&discount_id='
            . $discount->discount_id
            . '">duplicate</a></li>';
        }
        ?>
    </ul>
    <h2>Aktívne zľavy</h2>
    <ul>
        <?php
        foreach (Discount::getActive() as $discount)
        {
            echo "<li>"
            . formatSqlTimestamp($discount->timestamp_start)
            . " - "
            . $discount->title
            . '<a class="button duplicate" href="?page=discount_list&action=duplicate&discount_id='
            . $discount->discount_id
            . '">duplicate</a></li>';
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
                <a class="button duplicate" href="?page=discount_list&action=duplicate&discount_id=<?php echo $discount->discount_id; ?>">
                    duplicate
                </a>

                <a class="button delete" href="?page=discount_list&action=delete&discount_id=<?php echo $discount->discount_id; ?>">
                    delete
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
    <input type="hidden" name="discount_order" id="discount_order" value="" />
    <input type="submit" value="Updatni poradie" />
</form>
<div id="tooltip"></div>

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
