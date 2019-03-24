<?php

namespace Jennifer\Api;

interface ServiceInterface
{
    /**
     * Return map of the service class
     * @return array
     */
    public static function map();

    /**
     * @param $action
     * @return mixed
     */
    public function run($action);
}