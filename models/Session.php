<?php

require_once('User.php');

class Session {
  protected static $_logged_user = null;

  /**
   *
   * @return User
   */
  public static function getLoggedUser() {
    return self::$_logged_user;
  }

  public static function isLoggedIn() {
    return !is_null(self::$_logged_user);
  }

  public static function logIn($email, $password) {
    try {
      $user = User::getByEmail($email);
    } catch(Exception $e) {
      throw new Exception("No user with email \"" . $email . "\". " . $e->getMessage());
    }
    if($user->password != $password) {
      throw new Exception("Invalid password");
    }
    self::$_logged_user = $user;
  }

  public static function checkLogin() {
    if(isset($_SESSION['email'])) {
      try {
        $user = User::getByEmail($_SESSION['email']);
        self::$_logged_user = $user;
      } catch(Exception $e) {
        throw new Exception("Email \"" . $_SESSION['email'] . "\" is in session, but not in database. You dont exist anymore!");
      }
    }
  }

}

?>
