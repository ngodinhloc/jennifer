<?php
  namespace db\driver;

  class DriverFactory {
    /**
     * Load database driver
     * @param string $driverName
     * @param bool $devMode
     * @return \db\driver\DriverInterface
     */
    public function createDriver($driverName = null, $devMode = false) {
      switch ($driverName) {
        case "MySQL":
        default:
          $driver = new MySQL($devMode);
          break;
      }

      return $driver;
    }
  }