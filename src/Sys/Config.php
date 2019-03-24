<?php

namespace Jennifer\Sys;

use Jennifer\Sys\Exception\ConfigException;

/**
 * Class Config
 * @package Jennifer\Sys
 */
class Config
{
    protected $files;

    /**
     * Config constructor.
     * @param array $files
     * @throws \Jennifer\Sys\Exception\ConfigException
     */
    public function __construct($files = [])
    {
        $this->files = $files;
        try {
            $this->loadConfigs();
        } catch (ConfigException $exception) {
            throw $exception;
        }
    }

    /**
     * Load configs file to env
     * @throws \Jennifer\Sys\Exception\ConfigException
     */
    public function loadConfigs()
    {
        if (empty($this->files)) {
            throw new ConfigException(ConfigException::ERROR_MSG_REQUIRE_CONFIG_FILE);
        }
        foreach ($this->files as $file) {
            if (!file_exists($file)) {
                throw new ConfigException(ConfigException::ERROR_MSG_MISSING_CONFIG_FILE);
            }
            $envs = parse_ini_file($file, false);
            foreach ($envs as $env => $value) {
                putenv("{$env}={$value}");
            }
        }
    }

    /**
     * @param $name
     * @return array|false|string
     */
    public static function getConfig($name)
    {
        return getenv($name);
    }
}