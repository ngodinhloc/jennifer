<?php

namespace jennifer\db\driver;

use jennifer\exception\DBException;

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
        $host = getenv("DB_HOST");
        $user = getenv("DB_USER");
        $password = getenv("DB_PASSWORD");
        $name = getenv("DB_NAME");

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