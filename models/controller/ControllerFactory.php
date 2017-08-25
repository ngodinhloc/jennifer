<?php
namespace controller;
/**
 * Class ControllerFactory: create Controller
 * @package controller
 */
class ControllerFactory {
  /**
   * @param $controllerClass
   * @return \controller\ControllerInterface
   */
  public function createController($controllerClass) {
    $controller = new $controllerClass() or die("Controller not found: " . $controllerClass);

    return $controller;
  }
}