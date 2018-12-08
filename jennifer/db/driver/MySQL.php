<?php

namespace jennifer\db\driver;

use jennifer\exception\DBException;
use mysqli;

class MySQL implements DriverInterface
{
    /** @var \mysqli * */
    protected $mysqli;
    protected $devMode = true;

    const DB_ACTIONS = ["CHECK" => "CHECK TABLE",
        "ANALYZE" => "ANALYZE TABLE",
        "REPAIR" => "REPAIR TABLE",
        "OPTIMIZE" => "OPTIMIZE TABLE",];
    const QUERY_ERROR = "Error occurs when trying to query MySQL database";

    /**
     * MySQL constructor.
     * @param $host
     * @param $user
     * @param $password
     * @param $db string database name
     * @param bool $mode
     * @throws DBException
     */
    public function __construct($host, $user, $password, $db, $mode = false)
    {
        if (!$this->mysqli = new mysqli($host, $user, $password, $db)) {
            throw new DBException(DBException::ERROR_MSG_CONNECTION_FAILED, DBException::ERROR_CODE_CONNECTION_FAILED);
        }
        $this->devMode = $mode;
    }

    public function __destruct()
    {
        $this->mysqli->close();
    }

    /**
     * Get found rows from the most recent query
     * @return int
     * @throws DBException
     */
    public function getFoundRows()
    {
        $sql = "SELECT FOUND_ROWS()";
        $result = $this->query($sql);
        $foundRows = $result->fetch_row();

        return $foundRows[0];
    }

    /**
     * Private function query
     * @param string $sql
     * @return \mysqli_result
     * @throws DBException
     */
    public function query($sql = "")
    {
        $this->isDevMode($sql);
        if (!$result = $this->mysqli->query($sql)) {
            throw new DBException($this->getErrorMessage($sql), DBException::ERROR_CODE_QUERY_FAILED);
        }

        return $result;
    }

    /**
     * Echo the message in dev mode
     * @param string $sql
     */
    private function isDevMode($sql)
    {
        if ($this->devMode) {
            echo($sql . "<br>");
        }
    }

    /**
     * @param $sql
     * @return mixed|string
     */
    private function getErrorMessage($sql)
    {
        if ($this->devMode) {
            return $this->mysqli->error;
        }

        return self::QUERY_ERROR;
    }

    /**
     * Convert mysqli_result to array
     * @param \mysqli_result $result
     * @return array
     */
    public function resultToArray($result)
    {
        $arr = [];
        while ($row = $result->fetch_assoc()) {
            $arr[] = $row;
        }

        return $arr;
    }

    /**
     * Escape sql string before using in select
     * @param string $sql
     * @return string
     */
    public function escapeString($sql)
    {
        $sql = $this->mysqli->real_escape_string($sql);

        return $sql;
    }

    /**
     * @param $act
     * @return string
     * @throws DBException
     */
    public function checkDB($act)
    {
        $do = self::DB_ACTIONS["CHECK"];
        if (in_array($act, array_keys(self::DB_ACTIONS))) {
            $do = self::DB_ACTIONS[$act];
        }
        $sql = "SHOW TABLES";
        $result = $this->query($sql);
        $count = 0;
        $done = 0;
        while ($tables = $result->fetch_assoc()) {
            foreach ($tables as $db => $table) {
                $count += 1;
                $sql = "$do $table";
                $re = $this->query($sql);
                if ($re) {
                    $done += 1;
                }
            }
        }
        $st = $do . ': ' . $count . '. SUCCESS: ' . $done;

        return $st;
    }
}