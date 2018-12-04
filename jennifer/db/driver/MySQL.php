<?php

namespace jennifer\db\driver;

use jennifer\exception\DBException;
use jennifer\sys\Config;
use mysqli;

class MySQL implements DriverInterface
{
    /** @var \mysqli * */
    protected $mysqli;
    protected $devMode = true;
    private $messages = [
        "SERVER_ERROR" => "Could not connect to MySQL server",
        "QUERY_ERROR" => "Error occurs when trying to query MySQL database",
    ];
    const DB_ACTIONS = ["CHECK" => "CHECK TABLE",
        "ANALYZE" => "ANALYZE TABLE",
        "REPAIR" => "REPAIR TABLE",
        "OPTIMIZE" => "OPTIMIZE TABLE",];

    public function __construct($mode)
    {
        $this->devMode = $mode;
        $this->mysqli = new mysqli(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, Config::DB_NAME) or die($this->messages["SERVER_ERROR"]);
    }

    public function __destruct()
    {
        $this->mysqli->close();
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
        $result = $this->mysqli->query($sql) or die($this->getErrorMessage($sql));
        return $result;
    }

    /**
     * Get found rows from the most recent query
     * @return int
     */
    public function getFoundRows()
    {
        $sql = "SELECT FOUND_ROWS()";
        $result = $this->query($sql);
        $foundRows = $result->fetch_row();

        return $foundRows[0];
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

    /**
     * @param $sql
     * @return mixed|string
     */
    private function getErrorMessage($sql)
    {
        if ($this->devMode) {
            return $this->mysqli->error;
        }

        return $this->messages["QUERY_ERROR"];
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
}