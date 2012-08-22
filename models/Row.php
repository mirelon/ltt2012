<?php

require_once('Db.php');

class Row
{

    protected static $_table;
    protected static $_primary_key;
    protected $_data = array();

    public function __get($field)
    {
        if (!array_key_exists($field, $this->_data))
        {
            throw new Exception("Row has no field named \"" . $field . "\"");
        }
        return $this->_data[$field];
    }

    public function __set($field, $value)
    {
        $this->_data[$field] = $value;
    }

    public function unsetField($field)
    {
      if(array_key_exists($field, $this->_data)) {
        unset($this->_data[$field]);
      }
    }

    public function unsetFields($fields)
    {
      foreach($fields as $field) {
        $this->unsetField($field);
      }
    }

    protected static function getByFieldValue($field, $value)
    {
        $class = get_called_class();
        if (!isset($class::$_table))
        {
            throw new Exception('Class ' . $class . ' has no table set.');
        }
        $sql = sprintf('SELECT * FROM %s WHERE %s="%s"', escape($class::$_table), escape($field), escape($value)
        );
        $row = new $class();
        $row->_data = Db::fetchRow($sql);
        if (!isset($row->_data[$field]))
        {
            throw new Exception('Row with ' . $field . ' "' . $value . '" does not exist');
        }
        return $row;
    }

    public static function fromArray($data)
    {
        $class = get_called_class();
        $row = new $class();
        $row->_data = $data;
        return $row;
    }

    public static function fromArrayOfArray($datas)
    {
        $class = get_called_class();
        $rows = array();
        foreach ($datas as $data)
        {
            $rows [] = $class::fromArray($data);
        }
        return $rows;
    }

    public function getValue($field, $default = null)
    {
        if (!array_key_exists($field, $this->_data))
        {
            return $default;
        }
        return $this->_data[$field];
    }

    public static function getById($id)
    {
        $class = get_called_class();
        return $class::getByFieldValue($class::$_primary_key, $id);
    }

    public function save()
    {
        $class = get_called_class();
        if (!isset($this->_data[$class::$_primary_key]))
        {
            $sql = 'INSERT INTO ' . $class::$_table . '(`' . implode('`, `', array_keys($this->_data)) . '`) VALUES ("' . implode('", "', array_values($this->_data)) . '");';
        }
        else
        {
            $sets = array();
            foreach ($this->_data as $field => $value)
            {
                if ($field != $class::$_primary_key)
                {
                    $sets [] = '`' . $field . '` = "' . mysql_real_escape_string($value) . '"';
                }
            }
            $sql = 'UPDATE ' . $class::$_table . ' SET ' . implode(', ', $sets) . ' WHERE `' . $class::$_primary_key . '` = "' . $this->_data[$class::$_primary_key] . '";';
        }
        if (mysql_query($sql) === false)
        {
            throw new Exception("Could not save the item: " . mysql_error());
        }
    }

    public function delete()
    {
        $class = get_called_class();
        $sql = 'DELETE FROM ' . $class::$_table . ' WHERE `' . $class::$_primary_key . '` = "' . $this->_data[$class::$_primary_key] . '";';
        if(mysql_query($sql) === false)
        {
            throw new Exception("Could not delete the item: " . mysql_error());
        }
    }

    public function getDuplicate()
    {
        $class = get_called_class();
        $duplicate = new $class();
        foreach ($this->_data as $field => $value)
        {
            if ($field == $class::$_primary_key)
                continue;
            $duplicate->$field = $value;
        }
        return $duplicate;
    }

}

?>
