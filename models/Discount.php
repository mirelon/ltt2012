<?php

class Discount extends Row {

  protected static $_table = 'discounts';
  protected static $_primary_key = 'discount_id';

  public function getDuplicate() {
      $duplicate = parent::getDuplicate();
      $duplicate->timestamp_start = null;
      return $duplicate;
  }

}

?>
