<?php

class Bid extends Row {

  protected static $_table = 'bids';
  protected static $_primary_key = 'bid_id';

  
  public static function getByDiscountId($discount_id) {
      $sql = 'SELECT * FROM bids WHERE discount_id = ' . $discount_id;
      return self::fromArrayOfArray(Db::fetchAll($sql));
  }
}

?>
