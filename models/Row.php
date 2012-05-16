<?php

require_once('Db.php');

class Row {
  
  protected $_data;

  public function __get($field) {
    if(!array_key_exists($field, $this->_data)) {
      throw new Exception("Row has no field named \"" . $field . "\"");
    }
    return $this->_data[$field];
  }

  protected static function getByFieldValue($field, $value) {
    $class = get_called_class();
    if(!isset($class::$_table)) {
      throw new Exception('Class ' . $class . ' has no table set.');
    }
    $sql = sprintf('SELECT * FROM %s WHERE %s="%s"',
      escape($class::$_table),
      escape($field),
      escape($value)
    );
    $row = new $class();
    $row->_data = Db::fetchRow($sql);
    if(!isset($row->_data[$field])) {
      throw new Exception('Row with ' . $field . ' "' . $value . '" does not exist');
    }
    return $row;
  }

  protected static function getById($id) {
    $class = get_called_class();
    return $class::getByFieldValue($class::$_primary_key, $id);
  }

}
?>
