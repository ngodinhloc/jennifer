<?php
/**
 * Database class : this is the only model that has access to database by using mysqli
 */
namespace db;

use cache\FileCache;
use mysqli;

class DB {
  private $mysqli;
  private $tableName;
  private $sql;
  private $selectCols;
  private $insertCols;
  private $insertVals;
  private $updateVals;
  private $whereCond;
  private $orderBy;
  private $groupBy;
  private $innerjoin;
  private $leftJoin;
  private $rightJoin;
  private $offset;
  private $limit;
  private $result;
  private $foundRows;
  private $devMode = false;

  public function __construct() {
    $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Could not connect database");
  }

  public function __destruct() {
    $this->mysqli->close();
  }

  /**
   * Insert new record
   * <pre> $db->table('tbl_day')->columns([col1,col2])->values()->insert() </pre>
   * @return mixed
   */
  public function insert() {
    if (!$this->checkTable() || !$this->checkColumns() || !$this->checkValues()) {

      return false;
    }
    $table   = $this->tableName;
    $columns = $this->insertCols;
    $values  = $this->insertVals;
    $sql     = "INSERT INTO {$table}({$columns}) VALUES {$values}";

    return $this->query($sql);
  }

  /**
   * Get results:
   * <pre> $db->table('tbl_day')->select([col1,col2])->where([col1 => val1, col2 => val2])
   *                 ->groupBy([col1,col2])->orderBy([col1=>ASC,col2=>DESC])->offset(0)->limit(20)
   *                 ->get()->toArray();
   * </pre>
   * @param bool $foundRows : get found row or not
   * @param bool|string $cache :'mem' => Memchached, 'file' => FileCache;
   * @return $this|array
   */
  public function get($foundRows = false, $cache = false) {
    if (!$this->checkTable()) {
      return false;
    }
    $table   = $this->tableName;
    $select  = ($foundRows) ? "SELECT SQL_CALC_FOUND_ROWS" : "SELECT";
    $columns = ($this->selectCols) ? $this->selectCols : "*";
    $where   = ($this->whereCond) ? $this->whereCond : "";
    $groupBy = ($this->groupBy) ? $this->groupBy : "";
    $orderBy = ($this->orderBy) ? $this->orderBy : "";
    $limit   = "";
    if (is_numeric($this->limit)) {
      $offset = is_numeric($this->offset) ? $this->offset : 0;
      $limit  = " LIMIT {$offset}, {$this->limit}";
    }
    $sql = "{$select} {$columns} FROM {$table}{$where}{$groupBy}{$orderBy}{$limit}";

    switch($cache) {
      case "file":
        $data = FileCache::getCache($sql);
        if ($data) {
          $this->result = $data["data"];
          if ($foundRows) {
            $this->foundRows = $data["found"];
          }

          return $this;
        }
        else {
          $result = $this->query($sql);
          if ($foundRows) {
            $this->setFoundRows();
          }
          $this->result = $this->resultToArray($result);

          $data = ["found" => $this->foundRows, "data" => $this->result];
          FileCache::writeCache($sql, $data);

          return $this;
        }
        break;
      case "mem":
        break;
      default:
        $result = $this->query($sql);
        if ($foundRows) {
          $this->setFoundRows();
        }
        $this->result = $this->resultToArray($result);

        return $this;
        break;
    }
  }

  /**
   * Update table : $db->table()->where()->set()->update();
   * @return bool
   */
  public function update() {
    if (!$this->checkTable()) {
      return false;
    }
    $table = $this->tableName;
    $set   = $this->updateVals;
    $where = ($this->whereCond) ? $this->whereCond : "";
    $sql   = "UPDATE {$table}{$set}{$where}";

    return $this->query($sql);
  }

  /**
   * Delete records : $db->table->where->delete();
   * @return mixed
   */
  public function delete() {
    if (!$this->checkTable()) {
      return false;
    }
    $table = $this->tableName;
    $where = ($this->whereCond) ? $this->whereCond : "";
    $sql   = "DELETE FROM {$table}{$where}";

    return $this->query($sql);
  }

  /**
   * Private function query
   * @param string $sql
   * @return bool|\mysqli_result
   */
  private function query($sql = "") {
    if ($this->devMode) {
      echo($sql);
    }
    $result = $this->mysqli->query($sql) or die("Mysql syntax error: " . $sql);
    $this->result = $result;

    return $result;
  }

  /**
   * Order by statement
   * @param array $cols
   * <pre>prefixes : # => raw </pre>
   * @return $this
   */
  public function orderBy($cols = []) {
    $orders = " ORDER BY ";
    foreach ($cols as $col => $val) {
      $c = $this->getOp($col);

      switch($c) {
        case "#":
          $col = substr($col, 1);
          $op  = "{$col}";
          break;
        default:
          $op = "`{$col}`";
          break;
      }
      $orders .= "{$op} {$val}, ";
    }
    $orders        = substr($orders, 0, strlen($orders) - 2);
    $this->orderBy = $orders;

    return $this;
  }

  public function join($table1, $col1, $col) {

  }

  public function left($table1, $col1, $col) {

  }

  public function right($table1, $col1, $col) {

  }

  /**
   * Build the ('val1', 'val2') using when insert records
   * @param array $vals single array
   * <pre> prefix: # => raw </pre>
   * @return $this
   */
  private function value($vals = []) {
    $value = "(";
    foreach ($vals as $val) {
      $v = $this->getOp($val);

      switch($v) {
        case "#":
          $val = substr($val, 1);
          $op  = "{$val}";
          break;
        default:
          $op = "'{$val}'";
          break;
      }
      $value .= "{$op}, ";
    }
    $value = substr($value, 0, strlen($value) - 2);
    $value .= ")";

    return $value;
  }

  /**
   * Build the ('val1', 'val2'),('val1','val2'),... using when insert records
   * @param array $vals single or multiple array
   * @return $this
   */
  public function values($vals = []) {
    $values = "";
    if (is_array($vals[0])) {
      foreach ($vals as $val) {
        $values .= $this->value($val) . ", ";
      }
      $values = substr($values, 0, strlen($values) - 2);
    }
    else {
      $values = $this->value($vals);
    }
    $this->insertVals = $values;

    return $this;
  }

  /**
   * BSet table name
   * @param string $table
   * <pre>prefixes: # => raw</pre>
   * @return $this
   */
  public function table($table) {
    $op = substr($table, 0, 1);
    if ($op == "#") {
      $name = substr($table, 1);
    }
    else {
      $name = "`{$table}`";
    }
    $this->unsetVar();
    $this->tableName = $name;

    return $this;
  }

  /**
   * Select columns
   * @param array $cols
   * <pre>prefixes: # => raw</pre>
   * @return $this
   */
  public function select($cols = []) {
    $columns = "";
    foreach ($cols as $col) {
      $op = substr($col, 0, 1);
      if ($op == "#") {
        $col = substr($col, 1);
        $columns .= "{$col}, ";
      }
      else {
        $columns .= "`{$col}`, ";
      }
    }
    $columns          = substr($columns, 0, strlen($columns) - 2);
    $this->selectCols = $columns;

    return $this;
  }

  /**
   * Build the (col1,col2) using when insert records
   * @param array $cols
   * <pre>prefixes: # => raw</pre>
   * @return $this
   */
  public function columns($cols = []) {
    $columns = "";
    foreach ($cols as $col) {
      $op = substr($col, 0, 1);
      if ($op == "#") {
        $col = substr($col, 1);
        $columns .= "{$col}, ";
      }
      else {
        $columns .= "`{$col}`, ";
      }
    }
    $columns          = substr($columns, 0, strlen($columns) - 2);
    $this->insertCols = $columns;

    return $this;
  }

  /**
   * Build groupBy statement
   * @param array $cols
   * <pre>prefixes: # => raw</pre>
   * @return $this
   */
  public function groupBy($cols = []) {
    $groupBY = " GROUP BY ";
    foreach ($cols as $col) {
      $c = $this->getOp($col);
      switch($c) {
        case "#":
          $col = substr($col, 1);
          $op  = "{$col}";
          break;
        default:
          $op = "`{$col}`";
          break;
      }
      $groupBY .= "{$op}, ";
    }
    $groupBY       = substr($groupBY, 0, strlen($groupBY) - 2);
    $this->groupBy = $groupBY;

    return $this;
  }

  /**
   * Build the WHERE statement from array
   * <pre> column prefixes: default => AND, '|' => OR, '#' => raw
   * value prefixes: default => 'val' , '~' => LIKE, '!' => != , '@' => IN
   * </pre>
   * @param array $cond
   * @return $this
   */
  public function where($cond = []) {
    $where = " WHERE TRUE";
    foreach ($cond as $col => $val) {
      $c = $this->getOp($col);
      $v = $this->getOp($val);

      switch($c) {
        case "|":
          $op  = " OR ";
          $col = "`" . substr($col, 1) . "`";
          break;
        case "#":
          $op  = " AND ";
          $col = substr($col, 1);
          break;
        default:
          $op  = " AND ";
          $col = "`{$col}`";
          break;
      }

      switch($v) {
        case "!":
          $val  = substr($val, 1);
          $comp = $col . " != '{$val}'";
          break;
        case "~":
          $val  = substr($val, 1);
          $comp = $col . " LIKE '%{$val}%'";
          break;
        case "@":
          $val  = substr($val, 1);
          $comp = $col . " IN ({$val})";
          break;
        case "#":
          $val  = substr($val, 1);
          $comp = $col . " {$val}";
          break;
        default:
          $comp = $col . " = '{$val}'";
          break;
      }
      $where .= $op . $comp;
    }
    $this->whereCond = $where;

    return $this;
  }

  /**
   * Build the Set statement from array [column => val]
   * @param array $vals
   * <pre>prefixes: # => raw</pre>
   * @return $this
   */
  public function set($vals = []) {
    $set = " SET ";
    foreach ($vals as $col => $val) {
      $c = $this->getOp($col);
      $v = $this->getOp($val);

      switch($c) {
        case "#":
          $col = substr($col, 1);
          $op  = "{$col}";
          break;
        default:
          $op = "`{$col}`";
          break;
      }

      switch($v) {
        case "#":
          $val  = substr($val, 1);
          $comp = "{$val}";
          break;
        default:
          $comp = "'{$val}'";
          break;
      }
      $set .= "{$op} = {$comp}, ";
    }
    $set              = substr($set, 0, strlen($set) - 2);
    $this->updateVals = $set;

    return $this;
  }

  /**
   * Set limit
   * @param $limit
   * @return $this
   */
  public function limit($limit) {
    $this->limit = $limit;

    return $this;
  }

  /**
   * Set offset
   * @param $offset
   * @return $this
   */
  public function offset($offset) {
    $this->offset = $offset;

    return $this;
  }

  /**
   * unset all variables
   */
  private function unsetVar() {
    $this->sql        = null;
    $this->selectCols = null;
    $this->insertCols = null;
    $this->insertVals = null;
    $this->updateVals = null;
    $this->whereCond  = null;
    $this->orderBy    = null;
    $this->groupBy    = null;
    $this->innerjoin  = null;
    $this->leftJoin   = null;
    $this->rightJoin  = null;
    $this->offset     = null;
    $this->limit      = null;
    $this->result     = null;
    $this->foundRows  = null;
  }

  /**
   * Check table
   * @return bool
   */
  private function checkTable() {
    if (!$this->tableName) {
      echo("Please select table");

      return false;
    }
    else {
      return true;
    }
  }

  /**
   * Check insert values when insert
   * @return bool
   */
  private function checkValues() {
    if (!$this->insertVals) {
      echo("Please enter values to insert");

      return false;
    }
    else {
      return true;
    }
  }

  /**
   * Check columns when insert
   * @return bool
   */
  private function checkColumns() {
    if (!$this->insertCols) {
      echo("Please indicate columns to insert");

      return false;
    }
    else {
      return true;
    }
  }

  /**
   * Get the number of rows by the last query: only call after get
   * @return int
   */
  public function count() {
    if (!isset($this->result[0])) {
      return 1;
    }

    return count($this->result);
  }

  /**
   * @return mixed
   */
  public function foundRows() {
    if ($this->foundRows) {
      return $this->foundRows;
    }
    else {
      return false;
    }
  }

  /**
   * Set found rows from the most recent query
   * @return int
   */
  private function setFoundRows() {
    $sql             = "SELECT FOUND_ROWS()";
    $result          = $this->query($sql);
    $foundRows       = $result->fetch_row();
    $this->foundRows = $foundRows[0];
  }

  /**
   * Get the first char of (column|val)
   * @param string $op
   * @return string
   */
  private function getOp($op) {
    return substr($op, 0, 1);
  }

  /**
   * Escape sql string before using in select
   * @param string $sql
   * @return string
   */
  public function escapeString($sql) {
    $sql = $this->mysqli->real_escape_string($sql);

    return $sql;
  }

  /**
   * Get first array of result
   * @return mixed
   */
  public function first() {
    if ($this->result) {
      return $this->result[0];
    }
  }

  /**
   * Convert $this->result (array) to array with index and selected columns
   * @param string $index use column as index of the return array[index]
   * @param array $cols columns to get
   * @return array
   */
  public function toArray($index = null, $cols = null) {
    if ($this->result) {
      $arr = [];
      foreach ($this->result as $row) {
        if (isset($index)) {
          if (!empty($cols)) {
            $arr[$row[$index]] = array_intersect_key($row, array_flip($cols));
          }
          else {
            $arr[$row[$index]] = $row;
          }
        }
        else {
          if (!empty($cols)) {
            $arr[] = array_intersect_key($row, array_flip($cols));
          }
          else {
            $arr[] = $row;
          }
        }
      }

      return $arr;
    }
  }

  /**
   * Convert mysqli_result to array
   * @return \mysqli_result
   */
  private function resultToArray($result) {
    $arr = [];
    while ($row = $result->fetch_assoc()) {
      $arr[] = $row;
    }

    return $arr;
  }

  /**
   * Convert  array to array with index
   * @param array $arr
   * @param string $index use column as index of the return array[index]
   * @param array $keys keys to get
   * @return array
   */
  private function indexArray($arr = [], $index = null, $keys = null) {
    $newArr = [];
    if (isset($index)) {
      if (!empty($keys)) {
        $newArr[$arr[$index]] = array_intersect_key($arr, array_flip($keys));
      }
      else {
        $newArr[$arr[$index]] = $arr;
      }
    }
    else {
      if (!empty($keys)) {
        $newArr = array_intersect_key($arr, array_flip($keys));
      }
      else {
        $newArr = $arr;
      }
    }

    return $newArr;
  }

  /**
   * @param $act
   * @return string
   */
  public function checkDB($act) {
    $sql    = "SHOW TABLES";
    $result = $this->query($sql);

    if ($act == CHECK_DB) {
      $do = "CHECK TABLE";
    }
    if ($act == ANALYZE_DB) {
      $do = "ANALYZE TABLE";
    }
    if ($act == REPAIR_DB) {
      $do = "REPAIR TABLE";
    }
    if ($act == OPTIMIZE_DB) {
      $do = "OPTIMIZE TABLE";
    }
    $count = 0;
    $done  = 0;
    while ($tables = $result->fetch_assoc()) {
      foreach ($tables as $db => $table) {
        $count += 1;
        $sql = "$do $table";
        $re  = $this->query($sql);
        if ($re) {
          $done += 1;
        }
      }
    }
    $st = $do . ': ' . $count . '. SUCCESS: ' . $done;

    return $st;
  }
}