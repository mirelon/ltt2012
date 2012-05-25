<?php

require_once('models/Bid.php');

class Discount extends Row
{

    protected static $_table = 'discounts';
    protected static $_primary_key = 'discount_id';

    public function getDuplicate()
    {
        $duplicate = parent::getDuplicate();
        $duplicate->timestamp_start = null;
        $duplicate->assignNewOrder();
        return $duplicate;
    }

    public function assignNewOrder()
    {
        $sql = 'SELECT MAX(`order`)+1 FROM discounts;';
        $this->order = Db::fetchOne($sql);
        return $this;
    }

    public function hasStartedYet() {
        if (is_null($this->timestamp_start))
        {
            return false;
        }
        if (strtotime($this->timestamp_start) > time())
        {
            return false;
        }
        return true;
    }

    /**
     * Aktivne su take, co uz zacali a zaroven este nikto nevyhral.
     * Pre ne moze ale nemusi existovat bid.
     * @return boolean
     */
    public function isActive()
    {
        if(!$this->hasStartedYet()) {
            return false;
        }
        $sql = 'SELECT COUNT(*) FROM bids WHERE bids.winning = 1 AND bids.discount_id = ' . $this->discount_id;
        return (Db::fetchOne($sql) == 0);
    }

    /**
     * Posledna ponuka, pripadne vyvolavacia cena, ak nie je ziadna ponuka.
     * @return int
     */
    public function getLastBidPrice()
    {
        if(!$this->hasStartedYet()) {
            throw new Exception('Getting last bid price of discount not started yet');
        }
        try
        {
            return $this->getLastBid()->price;
        }
        catch (Exception $e)
        {
            if($this->price_drop_time == 0) {
                return $this->asking_price;
            }
            $passed = time() - strtotime($this->timestamp_start);
            $dropped = ceil($passed / $this->price_drop_time);
            return $this->asking_price - $dropped;
        }
    }

    public function getAllBids()
    {
        return Bid::getByDiscountId($this->discount_id);
    }

    /**
     * Return last bid for this discount, if exists. Throws an exception otherwise.
     * @return Bid
     * @throws Exception
     */
    public function getLastBid()
    {
        $sql = 'SELECT * FROM bids WHERE discount_id = ' . $this->discount_id . ' ORDER BY timestamp DESC LIMIT 1';
        try
        {
            return Bid::fromArray(Db::fetchRow($sql));
        }
        catch (Exception $e)
        {
            throw new Exception('getLastBid(): No bid for discount ' . $this->discount_id);
        }
    }

    /**
     * Return first bid for this discount, if exists. Throws an exception otherwise.
     * @return Bid
     * @throws Exception
     */
    public function getFirstBid()
    {
        $sql = 'SELECT * FROM bids WHERE discount_id = ' . $this->discount_id . ' ORDER BY timestamp ASC LIMIT 1';
        try
        {
            return Bid::fromArray(Db::fetchRow($sql));
        }
        catch (Exception $e)
        {
            throw new Exception('getFirstBid(): No bid for discount ' . $this->discount_id);
        }
    }

    /**
     * Datum, kedy to bude "predane" - ako timestamp, v pripade "never" hodi exception
     * @return int
     * @throws Exception
     */
    public function getBidValidity()
    {
        try
        {
            $bid = $this->getFirstBid();
        }
        catch (Exception $e)
        {
            throw new Exception("Discount is valid forever");
        }

        $timestamp = strtotime($bid->timestamp);
        /* @var $bid Bid */
        foreach ($this->getAllBids() as $bid)
        {
            $timestamp += $bid->extended_validity;
        }
        return $timestamp;
    }

    /**
     * Datum vo formate "j.n. H:i:s", pripadne string "Uvidíme"
     * @return string
     */
    public function getBidValidityString()
    {
        try
        {
            return date("j.n. H:i:s", $this->getBidValidity());
        }
        catch (Exception $e)
        {
            return "Uvidíme";
        }
    }

    /*     * ***********      STATIC FUNCTIONS      ************* */

    /**
     * Najde zlavy, ktorym vyprsal bid validity, a stavaju sa predanymi
     */
    public static function getNewWinning()
    {
        $sql = 'SELECT * FROM discounts WHERE timestamp_start<NOW() AND NOT EXISTS (SELECT * FROM bids WHERE bids.winning = 1 AND bids.discount_id = discounts.discount_id) AND discount(SELECT SUM(extended_validity) FROM bids WHERE bids.discount_id = discounts.discount_id)';
    }

    public static function getActiveCount()
    {
        $sql = 'SELECT COUNT(*) FROM discounts WHERE timestamp_start<NOW() AND NOT EXISTS (SELECT * FROM bids WHERE bids.winning = 1 AND bids.discount_id = discounts.discount_id);';
        return Db::fetchOne($sql);
    }

    public static function getActive()
    {
        $sql = 'SELECT * FROM discounts WHERE timestamp_start<NOW() AND NOT EXISTS (SELECT * FROM bids WHERE bids.winning = 1 AND bids.discount_id = discounts.discount_id) ORDER BY `order`;';
        return self::fromArrayOfArray(Db::fetchAll($sql));
    }

    public static function getFinished()
    {
        $sql = 'SELECT * FROM discounts WHERE timestamp_start<NOW() AND EXISTS (SELECT * FROM bids WHERE bids.winning = 1 AND bids.discount_id = discounts.discount_id) ORDER BY `order`;';
        return self::fromArrayOfArray(Db::fetchAll($sql));
    }

    public static function getInactive()
    {
        $sql = 'SELECT * FROM discounts WHERE timestamp_start>NOW() OR timestamp_start IS NULL ORDER BY `order`;';
        return self::fromArrayOfArray(Db::fetchAll($sql));
    }

}

?>
