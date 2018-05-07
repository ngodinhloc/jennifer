<?php

namespace jennifer\controller;

use jennifer\exception\RequestException;

/**
 * Class ControllerFactory: create Controller
 * @package jennifer\controller
 */
class ControllerFactory {
  /**
   * @param $controllerClass
   * @return \jennifer\controller\ControllerInterface
   * @throws RequestException
   */
  public function createController($controllerClass) {
    $controller = new $controllerClass();
    if ($controller) {
      return $controller;
    }
    throw new RequestException(RequestException::ERROR_MSG_INVALID_CONTROLLER, RequestException::ERROR_CODE_INVALID_CONTROLLER);
  }
}