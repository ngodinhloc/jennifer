<?php
/**
 * Each public function of controller class is an action
 */
namespace cons;

use sys\System;

class Controller {
  protected $requiredPermission = false;

  public function __construct() {
    $this->checkPermission();
  }

  /**
   * Check if user has permission to access controller
   * @return bool
   */
  protected function checkPermission() {
    // no permission required
    if (!$this->requiredPermission) {
      return true;
    }

    System::sessionStart();
    $permissionList = System::getPermission();
    if (!$permissionList) {
      die("User does not have permission to access this controller");
    }
    foreach ($this->requiredPermission as $per) {
      if (!in_array($per, $permissionList)) {
        die("User does not have permission to access this controller");
      }
    }

    return true;
  }

  /**
   * Get the required permissions for controller
   */
  protected function getRequiredPermission() {
    return $this->requiredPermission;
  }

  /**
   * Load required permission from database or set required permission on each controller
   */
  protected function loadRequiredPermission() {

  }
}