<?php

namespace jennifer\db\driver;

class DriverFactory
{
    /**
     * Load database driver
     * @param string $driverName
     * @param bool $devMode
     * @return \jennifer\db\driver\DriverInterface
     */
    public function createDriver($driverName = null, $devMode = false)
    {
        switch ($driverName) {
            case "MySQL":
            default:
                $driver = new MySQL($devMode, getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"), getenv("DB_NAME"));
                break;
        }

        return $driver;
    }
}