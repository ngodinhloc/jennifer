<?php

namespace jennifer\sys;

use jennifer\exception\ConfigException;

/**
 * Class Config
 * @package jennifer\sys
 */
class Config
{
    protected $files;

    /**
     * Config constructor.
     * @param array $files
     * @throws ConfigException
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
     * @throws ConfigException
     */
    public function loadConfigs()
    {
        if (empty($this->files)) {
            throw new ConfigException(ConfigException::ERROR_MSG_REQUIRE_CONFIG_FILE, ConfigException::ERROR_CODE_REQUIRED_CONFIG_FILE);
        }
        foreach ($this->files as $file) {
            if (!file_exists($file)) {
                throw new ConfigException(ConfigException::ERROR_MSG_MISSING_CONFIG_FILE, ConfigException::ERROR_MSG_MISSING_CONFIG_FILE);
            }
            $envs = parse_ini_file($file, false);
            foreach ($envs as $env => $value) {
                putenv("{$env}={$value}");
            }
        }
    }
}