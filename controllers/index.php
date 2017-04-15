<?php
/**
 * Single entry point for controllers: all ajax actions point to this page with a pair of {action, controller}
 */
require_once("../models/autoload.php");
use sys\System;

$para       = System::getPOST();
$action     = $para["action"];
$controller = $para["controller"];
$conClass   = System::loadController($controller);
if ($conClass) {
  $con = new $conClass() or die("Controller not found: " . $conClass);
  $con->$action($para);
}