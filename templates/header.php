<h1>Dražba zliav LTT 2012</h1>
<div class="navigation">
<?php foreach($pages as $page) { ?>
  <a href="<?php echo $page['url']; ?>">
  <div class="item rounded<?php if($current_page == $page['script'])echo " active"?>">
    <?php echo $page['nav']; ?>
  </div>
  </a>
<?php } ?>
</div>
