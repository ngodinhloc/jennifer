<?php
namespace api;
use auth\Authentication;

class Service {
  protected $requiredPermission = false;
  protected $authentication;
  protected $userData = [];
  protected $para = [];

  /**
   * Service constructor.
   * @param array $userData
   * @param array $para
   */
  public function __construct($userData, $para) {
    $this->userData       = $userData;
    $this->para           = $para;
    $this->authentication = new Authentication(Authentication::AUTH_TYPE_API);
    $this->authentication->checkServicePermission($this->userData["permission"], $this->requiredPermission);
  }

  /**
   * Check if get para exists then return value, else return false
   * @param $name
   * @return bool|mixed
   */
  protected function hasPara($name) {
    return isset($this->para[$name]) ? $this->para[$name] : false;
  }

  /**
   * Check required permission on action
   * @param bool|string $permission
   * @return bool
   */
  protected function requirePermission($permission = false) {
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