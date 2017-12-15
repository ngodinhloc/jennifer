<?php
namespace jennifer\db;

use jennifer\cache\CacheInterface;
use jennifer\cache\FileCache;
use jennifer\db\driver\DriverFactory;

/**
 * Class Database
 * @package db
 */
abstract class Database implements DatabaseInterface {
  /** @var string */
  private $defaultDriver = "MySQL";
  /** @var bool developing mode */
  private $devMode = false;
  /** @var \jennifer\db\driver\DriverInterface * */
  private $driver;
  /** @var  CacheInterface */
  protected $cacher;
  /** @var  string table name */
  protected $tableName;
  /** @var  string select columns */
  protected $selectCols;
  /** @var  string insert columns */
  protected $insertCols;
  /** @var  string insert values */
  protected $insertVals;
  /** @var  string update values */
  protected $updateVals;
  /** @var  string where conditions */
  protected $whereCond;
  /** @var  string order by */
  protected $orderBy;
  /** @var  string group by */
  protected $groupBy;
  /** @var  string inner join */
  protected $innerJoin;
  /** @var  string left join */
  protected $leftJoin;
  /** @var  string right join */
  protected $rightJoin;
  /** @var  int offset */
  protected $offset;
  /** @var  int limit */
  protected $limit;
  /** @var   array returned records */
  protected $result;
  /** @var  int found rows */
  protected $foundRows;

  const  QUERY_SELECT = "SELECT";
  const  QUERY_INSERT = "INSERT";
  const  QUERY_UPDATE = "UPDATE";
  const  QUERY_DELETE = "DELETE";

  /**
   * Database constructor.
   * @param \db\driver\DriverInterface
   */
  public function __construct($driver = null) {
    if ($driver) {
      $this->driver = $driver;
    }
    else {
      $factory      = new DriverFactory();
      $this->driver = $factory->createDriver($this->defaultDriver, $this->devMode);
    }
  }

  public function __destruct() {
    unset($this->driver);
  }

  /**
   * Private function query
   * @param string $sql
   * @return mixed $result
   * @see \jennifer\db\driver\DriverInterface::query()
   */
  private function query($sql = "") {
    $result = $this->driver->query($sql);

    return $result;
  }

  /**
   * Set found rows from the most recent query
   * @see \jennifer\db\driver\DriverInterface::getFoundRows()
   */
  private function setFoundRows() {
    $this->foundRows = $this->driver->getFoundRows();
  }

  /**
   * Convert mysqli_result to array
   * @param \mysqli_result $result
   * @return array
   * @see \jennifer\db\driver\DriverInterface::resultToArray()
   */
  private function resultToArray($result) {
    return $this->driver->resultToArray($result);
  }

  /**
   * Escape sql string before using in select
   * @param string $sql
   * @return string
   * @see \jennifer\db\driver\DriverInterface::escapeString()
   */
  public function escapeString($sql) {
    $sql = $this->driver->escapeString($sql);

    return $sql;
  }

  /**
   * Perform database check
   * @param $act
   * @return string
   * @see \jennifer\db\driver\DriverInterface::checkDB()
   */
  public function checkDB($act) {
    return $this->driver->checkDB($act);
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
    $sql = $this->buildQuery(self::QUERY_INSERT);

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
   * @return $this|bool
   */
  public function get($foundRows = false, $cache = false) {
    if (!$this->checkTable()) {
      return false;
    }
    $sql = $this->buildQuery(self::QUERY_SELECT, $foundRows);

    switch($cache) {
      case "file":
        $this->cacher = new FileCache();
        $data         = $this->cacher->getCache($sql);
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
          $data         = ["found" => $this->foundRows, "data" => $this->result];
          $this->cacher->writeCache($sql, $data);

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
   * @param string $type
   * @param boolean $foundRows
   * @return string
   */
  private function buildQuery($type, $foundRows = false) {
    switch($type) {
      case self::QUERY_UPDATE:
        $table = $this->tableName;
        $set   = $this->updateVals;
        $where = ($this->whereCond) ? $this->whereCond : "";
        $sql   = "UPDATE {$table}{$set} WHERE TRUE {$where}";
        break;
      case self::QUERY_DELETE:
        $table = $this->tableName;
        $where = ($this->whereCond) ? $this->whereCond : "";
        $sql   = "DELETE FROM {$table} WHERE TRUE {$where}";
        break;
      case self::QUERY_INSERT:
        $table   = $this->tableName;
        $columns = $this->insertCols;
        $values  = $this->insertVals;
        $sql     = "INSERT INTO {$table}({$columns}) VALUES {$values}";
        break;
      case self::QUERY_SELECT:
        $table     = $this->tableName;
        $select    = ($foundRows) ? "SELECT SQL_CALC_FOUND_ROWS" : "SELECT";
        $columns   = ($this->selectCols) ? $this->selectCols : "*";
        $innerJoin = ($this->innerJoin) ? $this->innerJoin : "";
        $leftJoin  = ($this->leftJoin) ? $this->leftJoin : "";
        $rightJoin = ($this->rightJoin) ? $this->rightJoin : "";
        $joins     = $innerJoin . $leftJoin . $rightJoin;
        $where     = ($this->whereCond) ? "WHERE TRUE " . $this->whereCond : "";
        $groupBy   = ($this->groupBy) ? "GROUP BY " . $this->groupBy : "";
        $orderBy   = ($this->orderBy) ? "ORDER BY " . $this->orderBy : "";
        $limit     = "";
        if (is_numeric($this->limit)) {
          $offset = is_numeric($this->offset) ? $this->offset : 0;
          $limit  = " LIMIT {$offset}, {$this->limit}";
        }
        $sql = "{$select} {$columns} FROM {$table} {$joins} {$where} {$groupBy} {$orderBy} {$limit}";
        break;
    }

    return $sql;
  }

  /**
   * Update table : $db->table()->where()->set()->update();
   * @return bool
   */
  public function update() {
    if (!$this->checkTable()) {
      return false;
    }
    $sql = $this->buildQuery(self::QUERY_UPDATE);

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
    $sql = $this->buildQuery(self::QUERY_DELETE);

    return $this->query($sql);
  }

  /**
   * Order by statement
   * @param array $cols
   * <pre>prefixes : # => raw </pre>
   * @return $this
   */
  public function orderBy($cols = []) {
    $orders = " ";
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
    $orders = substr($orders, 0, strlen($orders) - 2);
    if ($this->orderBy) {
      $this->orderBy .= "," . $orders;
    }
    else {
      $this->orderBy .= $orders;
    }

    return $this;
  }

  /**
   * Inner join : $this->table join $table on $this->table.$selfCol = $table.$tableCol
   * @param $table
   * @param $selfCol
   * @param $tableCol
   * @return $this
   */
  public function innerJoin($table, $selfCol, $tableCol) {
    $this->innerJoin .= " INNER JOIN {$table} ON {$this->tableName}.{$selfCol} = {$table}.{$tableCol}";

    return $this;
  }

  /**
   * Left join
   * @param $table
   * @param $selfCol
   * @param $tableCol
   * @return $this
   */
  public function leftJoin($table, $selfCol, $tableCol) {
    $this->leftJoin .= " LEFT JOIN {$table} ON {$this->tableName}.{$selfCol} = {$table}.{$tableCol}";

    return $this;
  }

  /**
   * Right join
   * @param $table
   * @param $selfCol
   * @param $tableCol
   *  * @return $this
   */
  public function rightJoin($table, $selfCol, $tableCol) {
    $this->rightJoin .= " RIGHT JOIN {$table} ON {$this->tableName}.{$selfCol} = {$table}.{$tableCol}";

    return $this;
  }

  /**
   * Build the ('val1', 'val2') using when insert records
   * @param array $vals single array
   * <pre> prefix: # => raw </pre>
   * @return string
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
    $groupBY = " ";
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
    $groupBY = substr($groupBY, 0, strlen($groupBY) - 2);
    if ($this->groupBy) {
      $this->groupBy .= "," . $groupBY;
    }
    else {
      $this->groupBy .= $groupBY;
    }

    return $this;
  }

  /**
   * Build the WHERE statement from array
   * <pre> column prefixes: default => AND, '|' => OR, '#' => raw
   * value prefixes: default => 'val' , '~' => LIKE, '!' => != , '@' => IN, '#' => raw
   * </pre>
   * @param array $conds
   * @return $this
   */
  public function where($conds = []) {
    $where = " ";
    foreach ($conds as $col => $val) {
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
        case ">":
          $val  = substr($val, 1);
          $comp = $col . " > '{$val}'";
          break;
        case "<":
          $val  = substr($val, 1);
          $comp = $col . " < '{$val}'";
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
    $this->whereCond .= $where;

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
   * Check table
   * @return bool
   */
  private function checkTable() {
    if (!$this->tableName) {
      return false;
    }

    return true;
  }

  /**
   * Check insert values when insert
   * @return bool
   */
  private function checkValues() {
    if (!$this->insertVals) {
      return false;
    }

    return true;
  }

  /**
   * Check columns when insert
   * @return bool
   */
  private function checkColumns() {
    if (!$this->insertCols) {
      return false;
    }

    return true;
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

    return false;
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
   * Get first array of result
   * @return mixed
   */
  public function first() {
    if ($this->result) {
      return $this->result[0];
    }

    return false;
  }

  /**
   * Convert $this->result (array) to array with index and selected columns
   * @param string $index use column as index of the return array[index]
   * @param array $cols columns to get
   * @return array|boolean
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

    return false;
  }
}