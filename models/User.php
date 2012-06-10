<?php

require_once('Row.php');

class User extends Row {

  public static $INITIAL_CREDITS = 100;

  protected static $_table = 'users';
  protected static $_primary_key = 'user_id';

  /**
   *
   * @return int
   */
  public function getDisponsibleCredits()
  {
      return self::$INITIAL_CREDITS - $this->getAmountPaid() - $this->getBlockedCredits();
  }

  /**
   * Credits in bids that are not winning, but are latest for corersponding discount.
   */
  public function getBlockedCredits()
  {
      $sql = '
          SELECT SUM(price)
          FROM bids as b1
          WHERE NOT EXISTS (
            SELECT * FROM bids AS b2
            WHERE b1.discount_id = b2.discount_id
            AND b1.timestamp < b2.timestamp
          )
          AND b1.winning = 0
          AND b1.price > 0
          AND b1.user_id = ' . $this->user_id;
      return Db::fetchOne($sql);
  }

  /**
   * Sum of price in all winning bids by this user.
   * @return int
   */
  public function getAmountPaid()
  {
      $sql = 'SELECT SUM(price) FROM bids WHERE winning = 1 AND user_id = ' . $this->user_id;
      return Db::fetchOne($sql);
  }

  /**
   *
   * @return array of Bid
   */
  public function getWinningBids()
  {
      $sql = 'SELECT * FROM bids WHERE winning = 1 AND user_id = ' . $this->user_id;
      return Bid::fromArrayOfArray(Db::fetchAll($sql));
  }

  /*     * ***********      STATIC FUNCTIONS      ************* */

  /**
   *
   * @param string $email
   * @return User
   */
  public static function getByEmail($email) {
    return self::getByFieldValue('email', $email);
  }

}

?>
