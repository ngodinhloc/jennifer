<?php
require_once("jennifer/sys/Config.php");

use jennifer\sys\Config;

spl_autoload_register(function ($className) {
  $namespace = str_replace("\\", "/", strtolower(__NAMESPACE__));
  $className = str_replace("\\", "/", $className);
  $class     = __DIR__ . "/" . (empty($namespace) ? "" : $namespace . "/") . $className . Config::MODEL_EXT;
  if (file_exists($class)) {
    require_once($class);
  }
});