<?php
/**
 * The base controller class: all controllers will extend this class
 * Each public function of controller class is an action
 */
namespace controller;

use auth\Authentication;
use io\Output;
use sys\Globals;

class Controller implements ControllerInterface {
  protected $authentication;
  protected $output;
  protected $requiredPermission = false;
  protected $userData = false;
  protected $post = [];

  public function __construct() {
    $this->authentication = new Authentication();
    $this->userData       = $this->authentication->getUserData();
    $this->authentication->checkUserPermission($this->requiredPermission, "controller");
    $this->post   = Globals::post();
    $this->output = new Output();
  }

  /**
   * Controller response
   * @param array|string $data
   * @param bool $json
   * @param int $jsonOpt
   */
  public function response($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES) {
    $this->output->ajax($data, $json, $jsonOpt);
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