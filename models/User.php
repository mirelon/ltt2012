<?php

require_once('Row.php');

class User extends Row
{

    public static $INITIAL_CREDITS = 100;
    protected static $_table = 'users';
    protected static $_primary_key = 'user_id';

    /**
     *
     * @return string
     */
    public function getFullName() {
        return $this->first_name . " " . $this->last_name;
    }

    /**
     * Adds row to table user_accesses. For debugging purposes only
     */
    public function logAccess() {
        $sql = 'INSERT INTO user_accesses (`user_id`, `url`) VALUES(' . $this->user_id . ', "' . escape($_SERVER['QUERY_STRING'] . " " . json_encode($_POST)) . '");';
        if(mysql_query($sql) === false) {
            throw new Exception(mysql_error());
        }
    }

    /**
     *
     * @param Discount $discount
     * @param int $price
     * @return User
     */
    public function addBid(Discount $discount, $price)
    {
        $extended_validity = $discount->getNextExtendedValidity();

        $bid = Bid::fromArray(array(
                    'user_id' => $this->user_id,
                    'discount_id' => $discount->discount_id,
                    'price' => $price,
                    'timestamp' => NOW(),
                    'extended_validity' => $extended_validity,
                    'winning' => 0
                ));
        $bid->save();
        return $this;
    }

    /**
     * User can bid on discount if
     * 1. The discount is active.
     * 2. Has enough disponsible credits.
     * 3. Last bid is not his.
     * @param Discount $discount
     * @return boolean
     */
    public function canBid(Discount $discount)
    {
        if (!$discount->isActive())
        {
            return false;
        }
        if (!$this->hasEnoughCreditsToBid($discount))
        {
            return false;
        }
        return !$this->hasLastBidForDiscount($discount);
    }

    /**
     *
     * @param Discount $discount
     * @return boolean
     */
    public function hasEnoughCreditsToBid(Discount $discount)
    {
        return ($this->getDisponsibleCredits() > $discount->getLastBidPrice());
    }

    /**
     *
     * @param Discount $discount
     * @return boolean
     */
    public function hasLastBidForDiscount(Discount $discount)
    {
        try
        {
            return ($discount->getLastBid()->user_id == $this->user_id);
        }
        catch (Exception $e)
        {
            return false;
        }
    }

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

    /**
     *
     * @return array of Discount
     */
    public function getWonDiscounts($only_not_printed = true)
    {
        $sql = '
            SELECT * FROM discounts
            LEFT JOIN bids ON discounts.discount_id = bids.discount_id
            WHERE bids.winning = 1';
        if($only_not_printed)$sql .= ' AND discounts.printed = 0';
        $sql .= ' AND bids.user_id = ' . $this->user_id . '
            ORDER BY bids.timestamp';
        return Discount::fromArrayOfArray(Db::fetchAll($sql));

    }

    /**
     *
     * @return boolean
     */
    public function hasSolved()
    {
      return $this->solved_timestamp != null;
    }

    /**
     *
     * @return User
     */
    public function logSolved()
    {
      $this->solved_timestamp = NOW();
      return $this;
    }

    /*     * ***********      STATIC FUNCTIONS      ************* */

    /**
     *
     * @param string $email
     * @return User
     */
    public static function getByEmail($email)
    {
        return self::getByFieldValue('email', $email);
    }

}

?>
