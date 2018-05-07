<?php

namespace jennifer\db\driver;

class sqlPDO implements DriverInterface {
  /** @var \pdo * */
  protected $pdo;
  protected $devMode = false;
  private $messages = [
    "SERVER_ERROR" => "Could not connect to SQL server",
    "QUERY_ERROR"  => "Error occur when trying to query the SQL database",
  ];
  const DB_ACTIONS = ["CHECK"    => "CHECK TABLE",
                      "ANALYZE"  => "ANALYZE TABLE",
                      "REPAIR"   => "REPAIR TABLE",
                      "OPTIMIZE" => "OPTIMIZE TABLE",];

  public function __construct() {

  }

  public function __destruct() {

  }

  /**
   * Private function query
   * @param string $sql
   */
  public function query($sql = "") {

  }

  /**
   * Get found rows from the most recent query
   * @return int
   */
  public function getFoundRows() {
  }

  /**
   * Convert mysqli_result to array
   * @param \mysqli_result $result
   * @return array
   */
  public function resultToArray($result) {
  }

  /**
   * Escape sql string before using in select
   * @param string $sql
   * @return string
   */
  public function escapeString($sql) {
  }

  /**
   * @param $act
   * @return string
   */
  public function checkDB($act) {

  }

  /**
   * @param $sql
   * @return mixed|string
   */
  private function getErrorMessage($sql) {
  }

  /**
   * Echo the message in dev mode
   * @param string $sql
   */
  private function isDevMode($sql) {
  }
}