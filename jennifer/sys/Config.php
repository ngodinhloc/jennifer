<?php

namespace jennifer\sys;

/**
 * Class Config
 * @package jennifer\sys
 */
class Config {
    protected $files;
    
    public function __construct($files = []) {
        $this->files = $files;
        $this->loadConfigs();
    }
    
    /**
     * Load configs file to env
     */
    public function loadConfigs() {
        foreach ($this->files as $file) {
            $envs = parse_ini_file($file, false);
            foreach ($envs as $env => $value) {
                putenv("{$env}={$value}");
            }
        }
    }
}