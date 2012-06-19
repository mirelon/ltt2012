<?php
require_once('models/Discount.php');
if (isset($_POST['price']) && isset($_POST['discount_id']))
{
    if (!is_numeric($_POST['price']))
        $error = 'Cena musí byť celé čislo';
    $bidding_discount = Discount::getById($_POST['discount_id']);
    if (!isset($error) && !$bidding_discount->isActive())
        $error = 'Zľava nie je aktívna, refreshni stránku';
    if (!isset($error) && $_POST['price'] <= $bidding_discount->getLastBidPrice())
        $error = 'Cena musí byť vyššia ako posledná ponuka';
    if (!isset($error) && $_POST['price'] > Session::getLoggedUser()->getDisponsibleCredits())
        $error = 'Toľko kreditov nemáš';
    if (!isset($error))
    {
//        echo 'Adding bid for user ' . Session::getLoggedUser()->user_id . ' and discount ' . $bidding_discount->discount_id . ' and price ' . $_POST['price'];
        Session::getLoggedUser()->addBid($bidding_discount, $_POST['price']);
    }
}
?>
Disponzibilný zostatok: <?php echo vysklonuj(Session::getLoggedUser()->getDisponsibleCredits(), 'kredit'); ?>.
<h2>Aktuálne zľavy:</h2>
<?php
Discount::processExpired();
Discount::processUpdateActiveCount();
$discounts = Discount::getActive();
/* @var $discount Discount */
foreach ($discounts as $discount)
{
    ?>
    <div class="discount rounded">
        <div class="left_part">
        <h3><?php echo $discount->title; ?></h3>
        Najvyššia ponuka:
        <?php
        echo $discount->getLastBidPrice();
        try
        {
            if (Session::getLoggedUser()->hasLastBidForDiscount($discount))
            {
                echo ' (tvoja)';
            }
        }
        catch (Exception $e)
        {

        }
        ?>
        <br/>
        Kedy bude predaná: <?php echo $discount->getBidValidityString(); ?><br/>
        <?php if (Session::getLoggedUser()->canBid($discount))
        { ?>
            <form action="" method="POST">
                <input title="Z intervalu <<?php echo ($discount->getLastBidPrice() + 1) . ',' . Session::getLoggedUser()->getDisponsibleCredits(); ?>>" type="text" name="price" class="price-input" value="<?php echo ($discount->getLastBidPrice() + 1); ?>" style="<?php if (!isset($error) || $discount->discount_id != $bidding_discount->discount_id) echo "display:none;"; ?>" />
                <input type="hidden" name="discount_id" value="<?php echo $discount->discount_id; ?>" />
                <input type="submit" value="Prihoď" />
        <?php if (isset($error) && $discount->discount_id == $bidding_discount->discount_id) echo '<span class="error">' . $error . '</span>'; ?>
            </form>
    <?php
    } else if (!Session::getLoggedUser()->hasEnoughCreditsToBid($discount))
    {
        echo "Nemáš dosť kreditov na prebitie ponuky.";
    }
    else if (Session::getLoggedUser()->hasLastBidForDiscount($discount))
    {
        echo "Posledná ponuka je tvoja, preto ju nemôžeš prebiť.";
    }
    ?>
    </div>
    <div class="right_part">
      <img src="<?php echo $discount->getImgSrc(); ?>" alt="" />
    </div>
    <div class="clear">
    </div>
    </div>
    <?php
}
?>

<script type="text/javascript">
    $('input[type=submit]').click(function(event, index) {
        if(!$(this).parent().find("input[name=price]").is(':visible')) {
            event.preventDefault();
            $(this).parent().find("input[name=price]").fadeIn().focus();
        }
    });
</script>

<script language="JavaScript">
    TargetDate = "06/30/2012 5:00 AM";
    BackColor = "#eeeeee";
    ForeColor = "#4A5C26";
    CountActive = true;
    CountStepper = -1;
    LeadingZero = true;
    DisplayFormat = "Countdown: %%D%% Days, %%H%% Hours, %%M%% Minutes, %%S%% Seconds.";
    FinishMessage = "It is finally here!";
</script>
<script language="JavaScript" src="http://scripts.hashemian.com/js/countdown.js"></script>
