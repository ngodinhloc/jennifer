<?php

namespace jennifer\api;

use http\Exception\RuntimeException;
use jennifer\exception\RequestException;

interface APIInterface
{
    /**
     * Process api request: map service and action before calling run();
     * @throws RequestException
     * @return mixed
     */
    public function processRequest();

    /**
     * Run service and response to api request
     * @return mixed
     */
    public function run();
}