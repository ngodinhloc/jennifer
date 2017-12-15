<?php
/**
 * Single entry point for controllers: all ajax actions point to this page with a pair of {action, controller}
 */
require_once("../models/autoload.php");

  use jennifer\controller\ControllerFactory;
  use jennifer\sys\System;

  list($controllerClass, $action) = System::loadController();
if ($controllerClass) {
  $factory    = new ControllerFactory();
  $controller = $factory->createController($controllerClass);
  $controller->action($action);
}