<h2>Aktuálne zľavy:</h2>
<?php
    require_once('models/Discount.php');
    $discounts = Discount::getActive();
    foreach($discounts as $discount) {
?>
<div class="discount rounded">
    <h3><?php echo $discount->title; ?></h3>
    Druhá najvyššia ponuka: ___<br/>
    Kedy bude predaná: ___<br/>
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