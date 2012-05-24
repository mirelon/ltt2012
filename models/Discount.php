<?php

class Discount extends Row {

  protected static $_table = 'discounts';
  protected static $_primary_key = 'discount_id';

  public function getDuplicate() {
      $duplicate = parent::getDuplicate();
      $duplicate->timestamp_start = null;
      $duplicate->assignNewOrder();
      return $duplicate;
  }

  public function assignNewOrder() {
      $sql = 'SELECT MAX(`order`)+1 FROM discounts;';
      $this->order = Db::fetchOne($sql);
      return $this;
  }

  public static function getActiveCount() {
      $sql = 'SELECT COUNT(*) FROM discounts WHERE timestamp_start<NOW() AND NOT EXISTS (SELECT * FROM bids WHERE bids.winning = 1 AND bids.discount_id = discounts.discount_id);';
      return Db::fetchOne($sql);
  }

  public static function getActive() {
      $sql = 'SELECT * FROM discounts WHERE timestamp_start<NOW() AND NOT EXISTS (SELECT * FROM bids WHERE bids.winning = 1 AND bids.discount_id = discounts.discount_id) ORDER BY `order`;';
      return self::fromArrayOfArray(Db::fetchAll($sql));
  }

  public static function getFinished() {
      $sql = 'SELECT * FROM discounts WHERE timestamp_start<NOW() AND EXISTS (SELECT * FROM bids WHERE bids.winning = 1 AND bids.discount_id = discounts.discount_id) ORDER BY `order`;';
      return self::fromArrayOfArray(Db::fetchAll($sql));
  }

  public static function getInactive() {
      $sql = 'SELECT * FROM discounts WHERE timestamp_start>NOW() OR timestamp_start IS NULL ORDER BY `order`;';
      return self::fromArrayOfArray(Db::fetchAll($sql));
  }

  public function isActive() {
      if(is_null($this->timestamp_start)) {
          return false;
      }
      if(strtotime($this->timestamp_start) > time()) {
          return false;
      }
      $sql = 'SELECT COUNT(*) FROM bids WHERE bids.winning = 1 AND bids.discount_id = ' . $this->discount_id;
      return (Db::fetchOne($sql) == 0);
  }

  /**
   * Datum, kedy to bude "predane"
   */
  public function getBidValidity() {

  }

  public static function getNewWinning() {
      $sql = 'SELECT * FROM discounts WHERE timestamp_start<NOW() AND NOT EXISTS (SELECT * FROM bids WHERE bids.winning = 1 AND bids.discount_id = discounts.discount_id) AND discount(SELECT SUM(extended_validity) FROM bids WHERE bids.discount_id = discounts.discount_id)';
  }

}

?>
