<?php

namespace Jennifer\Controller;

interface ControllerInterface
{
    /**
     * @param string $action
     * @throws \Jennifer\Controller\Exception\ControllerException
     */
    public function action(string $action);
}