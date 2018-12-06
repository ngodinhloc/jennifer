<?php

namespace jennifer\api;

/**
 * Class ServiceFactory
 * @package jennifer\api
 */
use jennifer\exception\RequestException;

class ServiceFactory {
    /**
     * Create service
     * @param $serviceClass
     * @param $userData
     * @param $para
     * @return \jennifer\api\ServiceInterface;
     * @throws RequestException
     */
    public function createService($serviceClass, $userData, $para) {
        $service = new $serviceClass($userData, $para);
        if ($service) {
            return $service;
        }
        throw new RequestException(RequestException::ERROR_MSG_INVALID_SERVICE, RequestException::ERROR_CODE_INVALID_SERVICE);
    }
}