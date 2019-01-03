<?php

namespace jennifer\db\driver;

use jennifer\exception\DBException;
use jennifer\sys\Config;

class DriverFactory
{
    /**
     * Load database driver
     * @param string $driverName
     * @param bool $devMode
     * @return DriverInterface
     * @throws DBException
     */
    public function createDriver($driverName, $devMode = false)
    {
        $host = Config::getConfig("DB_HOST");
        $user = Config::getConfig("DB_USER");
        $password = Config::getConfig("DB_PASSWORD");
        $name = Config::getConfig("DB_NAME");

        if (!$host || !$user || !$password || !$name) {
            throw new DBException(DBException::ERROR_MSG_MISSING_CONFIG, DBException::ERROR_CODE_MISSING_CONFIGS);
        }
        switch ($driverName) {
            case "MySQL":
            default:
                try {
                    $driver = new MySQL($host, $user, $password, $name, $devMode);
                } catch (DBException $exception) {
                    throw $exception;
                }
                break;
        }

        return $driver;
    }
}