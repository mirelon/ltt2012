<h2>Aktuálne zľavy:</h2>
<?php
    require_once('models/Discount.php');
    Discount::processUpdateActiveCount();
    $discounts = Discount::getActive();
    /* @var $discount Discount */
    foreach($discounts as $discount) {
?>
<div class="discount rounded">
    <h3><?php echo $discount->title; ?></h3>
    Najvyššia ponuka: <?php echo $discount->getLastBidPrice(); ?><br/>
    Kedy bude predaná: <?php echo $discount->getBidValidityString(); ?><br/>
    Prihoď
</div>
<?php
    }
?>
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
