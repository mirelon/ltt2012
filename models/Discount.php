<?php

require_once('models/Bid.php');

class Discount extends Row
{

    public static $NUMBER_OF_CONCURRENT_DISCOUNTS = 3;
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

    public function hasStartedYet()
    {
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
        if (!$this->hasStartedYet())
        {
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
        if (!$this->hasStartedYet())
        {
            throw new Exception('Getting last bid price of discount not started yet');
        }
        try
        {
            return $this->getLastBid()->price;
        }
        catch (Exception $e)
        {
            if ($this->price_drop_time == 0)
            {
                return $this->asking_price;
            }
            $passed = time() - strtotime($this->timestamp_start);
            $dropped = ceil($passed / $this->price_drop_time);
            return $this->asking_price - $dropped;
        }
    }

    /**
     *
     * @return int
     */
    public function getNextExtendedValidity()
    {
        try
        {
            $extended_validity = $this->getLastBid()->extended_validity - $this->bid_validity_decay;
        }
        catch (Exception $e)
        {
            $extended_validity = $this->bid_initial_validity;
        }
        if ($extended_validity < 0)
        {
            $extended_validity = 0;
        }
        return $extended_validity;
    }

    public function getAllBids()
    {
        return Bid::getByDiscountId($this->discount_id);
    }

    public function hasBid()
    {
        $sql = 'SELECT COUNT(*) FROM bids WHERE discount_id = ' . $this->discount_id;
        return Db::fetchOne($sql)>0;
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
            return formatTimestamp($this->getBidValidity());
        }
        catch (Exception $e)
        {
            return "Ešte nikto neprejavil záujem.";
        }
    }

    /**
     *
     * @return Discount
     */
    public function setStartedNow()
    {
        $this->timestamp_start = NOW();
        return $this;
    }

    /*     * ***********      STATIC FUNCTIONS      ************* */

    /**
     * Najde zlavy, ktorym vyprsal bid validity, a stavaju sa predanymi
     */
    public static function getNewWinning()
    {
        $sql = 'SELECT * FROM discounts WHERE timestamp_start!="0000-00-00 00:00:00" AND timestamp_start<=NOW() AND NOT EXISTS (SELECT * FROM bids WHERE bids.winning = 1 AND bids.discount_id = discounts.discount_id) AND discount(SELECT SUM(extended_validity) FROM bids WHERE bids.discount_id = discounts.discount_id)';
    }

    public static function getActiveCount()
    {
        $sql = 'SELECT COUNT(*) FROM discounts WHERE timestamp_start!="0000-00-00 00:00:00" AND timestamp_start<=NOW() AND NOT EXISTS (SELECT * FROM bids WHERE bids.winning = 1 AND bids.discount_id = discounts.discount_id);';
        return Db::fetchOne($sql);
    }

    public static function getActive()
    {
        $sql = 'SELECT * FROM discounts WHERE timestamp_start!="0000-00-00 00:00:00" AND timestamp_start<=NOW() AND NOT EXISTS (SELECT * FROM bids WHERE bids.winning = 1 AND bids.discount_id = discounts.discount_id) ORDER BY `order`;';
        return self::fromArrayOfArray(Db::fetchAll($sql));
    }

    /**
     * Discounts that are sold (exists winning bid for it).
     * @return array of Discount
     */
    public static function getFinished()
    {
        $sql = 'SELECT * FROM discounts WHERE timestamp_start<NOW() AND EXISTS (SELECT * FROM bids WHERE bids.winning = 1 AND bids.discount_id = discounts.discount_id) ORDER BY `order`;';
        return self::fromArrayOfArray(Db::fetchAll($sql));
    }

    /**
     * Discounts that are not started yet.
     * @return array of Discount
     */
    public static function getInactive()
    {
        $sql = 'SELECT * FROM discounts WHERE timestamp_start>NOW() OR timestamp_start IS NULL OR timestamp_start="0000-00-00 00:00:00" ORDER BY `order` ASC;';
        return self::fromArrayOfArray(Db::fetchAll($sql));
    }

    /**
     * Returns first discount that is suited to be active.
     * @return Discount
     * @throws Exception
     */
    public static function getFirstInactive()
    {
        $discounts = self::getInactive();
        if (count($discounts) == 0)
        {
            throw new Exception("getFirstInactive: no inactive discounts to get.");
        }
        return $discounts[0];
    }

    /**
     * Count of discounts that are not started yet.
     * @return int
     */
    public static function getInactiveCount()
    {
        $sql = 'SELECT COUNT(*) FROM discounts WHERE timestamp_start>NOW() OR timestamp_start IS NULL OR timestamp_start="0000-00-00 00:00:00" ORDER BY `order`;';
        return Db::fetchOne($sql);
    }

    /**
     * Activates new discount.
     */
    protected static function _processAddNewActiveDiscount()
    {
        self::getFirstInactive()->setStartedNow()->save();
    }

    /**
     * Activates discounts until their number is restored to $NUMBER_OF_CONCURRENT_DISCOUNTS (as for 10.6., =3)
     */
    public static function processUpdateActiveCount()
    {
        while (self::getInactiveCount() > 0 && self::getActiveCount() < self::$NUMBER_OF_CONCURRENT_DISCOUNTS)
        {
            // "Need to activate new discount, because of active count=" . self::getActiveCount();
            self::_processAddNewActiveDiscount();
        }
    }

}

?>
