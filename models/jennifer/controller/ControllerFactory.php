<?php
namespace jennifer\controller;
/**
 * Class ControllerFactory: create Controller
 * @package jennifer\controller
 */
class ControllerFactory {
  /**
   * @param $controllerClass
   * @return \jennifer\controller\ControllerInterface
   */
  public function createController($controllerClass) {
    $controller = new $controllerClass() or die("Controller not found: " . $controllerClass);

    return $controller;
  }
}