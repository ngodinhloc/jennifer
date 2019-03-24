<?php

namespace Jennifer\Db\Driver;

use Jennifer\Db\Exception\DbException;
use Jennifer\Sys\Config;

class DriverFactory
{
    /**
     * Load database driver
     * @param string $driverName
     * @param bool $devMode
     * @return \Jennifer\Db\Driver\DriverInterface
     * @throws \Jennifer\Db\Exception\DbException
     */
    public function createDriver($driverName, $devMode = false)
    {
        $host = Config::getConfig("DB_HOST");
        $user = Config::getConfig("DB_USER");
        $password = Config::getConfig("DB_PASSWORD");
        $name = Config::getConfig("DB_NAME");

        if (!$host || !$user || !$password || !$name) {
            throw new DbException(DbException::ERROR_MSG_MISSING_CONFIG);
        }
        switch ($driverName) {
            case "MySQL":
            default:
                try {
                    $driver = new MySQL($host, $user, $password, $name, $devMode);
                } catch (DbException $exception) {
                    throw $exception;
                }
                break;
        }

        return $driver;
    }
}