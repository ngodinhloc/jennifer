<?php

namespace Jennifer\Controller;

use Jennifer\Controller\Exception\ControllerException;

/**
 * Class ControllerFactory: create Controller
 * @package Jennifer\Controller
 */
class ControllerFactory
{
    /**
     * @param $controllerClass
     * @return \Jennifer\Controller\ControllerInterface
     * @throws \Jennifer\Controller\Exception\ControllerException
     */
    public function createController($controllerClass)
    {
        $controller = new $controllerClass();
        if ($controller) {
            return $controller;
        }
        throw new ControllerException(ControllerException::ERROR_MSG_INVALID_CONTROLLER);
    }
}