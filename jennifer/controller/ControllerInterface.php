<?php

namespace jennifer\controller;

use jennifer\exception\RequestException;

interface ControllerInterface
{
    /**
     * @throws RequestException
     */
    public function action($action);
}