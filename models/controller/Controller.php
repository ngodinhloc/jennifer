<?php
namespace controller;

use auth\Authentication;
use io\Output;
use sys\Globals;

/**
 * The base controller class: all controllers will extend this class
 * Each public function of controller class is an action
 * @package controller
 */
class Controller implements ControllerInterface {
  /** @var Authentication */
  protected $authentication;
  /** @var Output */
  protected $output;
  /** @var array|bool usr data */
  protected $userData = false;
  /** @var array|bool|string _POST */
  protected $post = [];
  /** @var bool|array required permission */
  protected $requiredPermission = false;

  public function __construct() {
    $this->authentication = new Authentication();
    $this->authentication->checkUserPermission($this->requiredPermission, "controller");
    $this->userData = $this->authentication->getUserData();
    $this->post     = Globals::post();
    $this->output   = new Output();
  }

  /**
   * Run the action
   * @param $action
   */
  public function action($action) {
    $this->$action();
  }

  /**
   * Controller response
   * @param array|string $data
   * @param bool $json
   * @param int $jsonOpt
   */
  protected function response($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES) {
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