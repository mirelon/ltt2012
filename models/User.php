<?php

require_once('Row.php');

class User extends Row {

  protected static $_table = 'users';
  protected static $_primary_key = 'user_id';

  public static function getByEmail($email) {
    return self::getByFieldValue('email', $email);
  }

}

?>
