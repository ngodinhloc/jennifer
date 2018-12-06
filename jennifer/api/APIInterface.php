<?php

namespace jennifer\api;

interface APIInterface {
    /**
     * Process api request: map service and action before calling run();
     * @param $req
     * @return mixed
     */
    public function process($req);
    
    /**
     * Run service and response to api request
     * @return mixed
     */
    public function run();
}