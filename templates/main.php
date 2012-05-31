<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>
<?php
  if(Session::isLoggedIn()) {
    echo "Dražba zliav LTT 2012";
  } else {
    echo 'Letný tábor trojstenu 2012';
  }
?>
</title>
<link rel="stylesheet" href="templates/style.css" />
<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="js/ui/jquery-ui-1.8.20.custom.js"></script>
</head>
<body>
<div class="wrapper">
<div class="header rounded">
<?php require_once('header.php'); ?>
</div>
<div class="content rounded">
<?php require_once($current_page); ?>
</div>
</div>
</body>
</html>
