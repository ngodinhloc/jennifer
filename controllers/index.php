<?php
  /**
   * Single entry point for controllers: all ajax actions point to this page with a pair of {action, controller}
   */
  require_once("../models/autoload.php");
  use sys\System;

  list($controller, $action) = System::loadController();
  if ($controller) {
    $con = new $controller() or die("Controller not found: " . $controller);
    $con->$action();
  }