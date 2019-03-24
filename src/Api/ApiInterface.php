<?php

namespace Jennifer\Api;

interface ApiInterface
{
    /**
     * Process api request: map service and action before calling run();
     * @throws \Jennifer\Http\Exception\RequestException
     * @return mixed
     */
    public function processRequest();

    /**
     * Run service and response to api request
     * @return mixed
     */
    public function run();
}