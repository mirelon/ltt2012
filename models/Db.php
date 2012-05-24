<?php

require_once('Utils.php');

class Db
{

    public static $user;
    public static $password;
    public static $host;
    public static $dbname;
    public static $link;

    public static function init()
    {
        self::$link = mysql_connect(self::$host, self::$user, self::$password) or die('Could not connect to database.');
        mysql_select_db(self::$dbname);
    }

    public static function terminate()
    {
        mysql_close(self::$link);
    }

    public static function query($sql)
    {
        mysql_query($sql);
    }

    public static function fetchAll($sql)
    {
        $result = mysql_query($sql);
        $rows = array();
        while ($row = mysql_fetch_assoc($result))
        {
            $rows [] = $row;
        }
        return $rows;
    }

    public static function fetchRow($sql)
    {
        $result = mysql_query($sql);
        if ($result === false)
        {
            throw new Exception('No row fetched');
        }
        return mysql_fetch_assoc($result);
    }

    public static function fetchOne($sql)
    {
        $result = mysql_query($sql);
        if ($result === false)
        {
            throw new Exception('No row fetched');
        }
        $array = mysql_fetch_row($result);
        return $array[0];
    }

    public static function getUserId($email, $password)
    {
        if (empty($login))
            throw new Exception('No username available.');
        $sql = sprintf('SELECT `user_id` FROM `users` WHERE `user_email` = "%s" AND `user_password` = "%s"', escape($email), escape($password)
        );
        $row = Db::fetchRow($sql);
        if (!isset($row['user_id']))
        {
            throw new Exception("Invalid email/password");
        }
        return $row['user_id'];
    }

    public static function addAccess()
    {
        $sql = 'INSERT INTO `user_accesses` (`user_id`, `user_access_timestamp`) VALUES (' . escape($_SESSION['user_id']) . ', NOW())';
        mysql_query($sql);
    }

    public static function getUserCount()
    {
        $sql = 'SELECT COUNT(`user_id`) AS `count` FROM `users`;';
        $row = Db::fetchRow($sql);
        return $row['count'];
    }

}

?>
