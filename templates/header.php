<h1>
<?php
  if(Session::isLoggedIn()) {
    echo 'Dražba zliav LTT 2012';
  } else {
    echo 'Letný tábor trojstenu 2012';
  }
?>
</h1>
<div class="navigation">
<?php foreach($pages as $page) if(!is_null($page['nav'])) { ?>
  <a href="<?php echo $page['url']; ?>">
  <div class="item rounded<?php if($current_page_script == $page['script'])echo " active"?>">
    <?php echo $page['nav']; ?>
  </div>
  </a>
<?php } ?>
</div>
