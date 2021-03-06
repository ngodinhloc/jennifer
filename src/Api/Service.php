<?php

namespace Jennifer\Api;

use Jennifer\Api\Exception\ServiceException;
use Jennifer\Auth\Authentication;

/**
 * Class Service: all services must extend this class
 * @package Jennifer\Api
 */
class Service
{
    /** @var  string public name of the service */
    protected static $serviceName;
    /** @var Authentication */
    protected $authentication;
    /** @var array user data */
    protected $userData = [];
    /** @var array service parameters */
    protected $para = [];
    /** @var bool|array required permission */
    protected $requiredPermission = false;

    /**
     * Service constructor.
     * @param array $userData
     * @param array $para
     * @throws \Jennifer\Api\Exception\ServiceException
     */
    public function __construct($userData, $para)
    {
        $this->userData = $userData;
        $this->para = $para;
        $this->authentication = new Authentication(Authentication::AUTH_TYPE_API);
        try {
            $this->authentication->checkServicePermission($this->userData["permission"], $this->requiredPermission);
        } catch (ServiceException $exception) {
            throw $exception;
        }
    }

    /**
     * Run the service
     * @param $action
     * @return mixed
     */
    public function run($action)
    {
        return $this->$action();
    }

    /**
     * Check if get para exists then return value, else return false
     * @param $name
     * @return bool|mixed
     */
    protected function hasPara($name)
    {
        return isset($this->para[$name]) ? $this->para[$name] : false;
    }

    /**
     * Check required permission on action
     * @param bool|string $permission
     * @return bool
     */
    protected function requirePermission($permission = false)
    {
        if (!$permission) {
            return true;
        }

        $userPermission = $this->userData["permission"];
        if (empty($userPermission)) {
            die($this->authentication->messages["NO_PERMISSION_API"]["message"]);
        }

        if (!in_array($permission, $userPermission)) {
            die($this->authentication->messages["NO_PERMISSION_API"]["message"]);
        }

        return true;
    }
}