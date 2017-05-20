<?php
/**
 * Each public function of controller class is an action
 */
namespace cons;

use sys\System;

class Controller implements ControllerInterface {
  protected $requiredPermission = false;
  protected $userData = false;
  protected $post = [];
  protected $messages = [
    "NO_PERMISSION" => ["message" => "You do not have permission to access this controller."],
  ];

  public function __construct() {
    $this->checkPermission();
    $this->post = System::getPOST();
  }

  /**
   * Controller response
   * @param array|string $data
   * @param bool $json
   * @param int $jsonOpt
   */
  public function response($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES) {
    if (is_array($data)) {
      if ($json) {
        header('Content-Type: application/json');
        echo(json_encode($data, $jsonOpt));
        exit;
      }
      echo(json_encode($data, $jsonOpt));
      exit;
    }
    echo $data;
    exit;
  }

  /**
   * Check if user has permission to access controller
   * @return bool
   */
  protected function checkPermission() {
    $this->userData = System::checkJWT();

    // no permission required
    if (!$this->requiredPermission) {
      return true;
    }

    // there is required permissions of the controller
    $userPermission = false;
    if ($this->userData) {
      $userPermission = explode(",", $this->userData->permission);
    }

    // if user has no permission data
    if (!$userPermission) {
      die($this->messages["NO_PERMISSION"]);
    }

    // check each required permission against user permission
    foreach ($this->requiredPermission as $per) {
      if (!in_array($per, $userPermission)) {
        die($this->messages["NO_PERMISSION"]);
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