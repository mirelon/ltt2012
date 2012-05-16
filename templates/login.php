<?php
if(isset($_POST) && isset($_POST['email']) && isset($_POST['password'])) {
  try {
    Session::logIn($_POST['email'], $_POST['password']);
    $_SESSION['email'] = $_POST['email'];
  } catch(Exception $e) {
    echo $e->getMessage();
  }
}

if(Session::isLoggedIn()) {
  header('Location: ' . $base_url);
} else {
?>
<form action="" method="post">
<table cellpadding="0" cellspacing="0">
<tr><td>Email:</td><td><input type="text" name="email" value="
<?php
  if(isset($_POST['email'])) {
    echo $_POST['email'];
  }
?>
"></input></td></tr>
<tr><td>Heslo:</td><td><input type="password" name="password"></input></td></tr>
<tr><td></td><td><button type="submit">Login</button></td></tr>
</table>
</form>
<?php
}
?>
