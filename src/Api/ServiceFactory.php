<?php

namespace Jennifer\Api;

use Jennifer\Api\Exception\ServiceException;

/**
 * Class ServiceFactory
 * @package Jennifer\Api
 */
class ServiceFactory
{
    /**
     * Create service
     * @param $serviceClass
     * @param $userData
     * @param $para
     * @return \Jennifer\Api\ServiceInterface;
     * @throws \Jennifer\Api\Exception\ServiceException
     */
    public function createService($serviceClass, $userData, $para)
    {
        $service = new $serviceClass($userData, $para);
        if ($service) {
            return $service;
        }
        throw new ServiceException(ServiceException::ERROR_MSG_INVALID_SERVICE);
    }
}