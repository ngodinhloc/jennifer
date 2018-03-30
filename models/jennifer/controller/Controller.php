<?php

namespace jennifer\controller;

use jennifer\auth\Authentication;
use jennifer\http\Request;
use jennifer\io\Output;

/**
 * The base controller class: all controllers will extend this class
 * Each public function of controller class is an action
 * @package jennifer\controller
 */
class Controller implements ControllerInterface {
  /** @var Authentication */
  protected $authentication;
  /** @var  Request */
  protected $request;
  /** @var Output */
  protected $output;
  /** @var array|bool usr data */
  protected $userData = false;
  /** @var bool|array required permission */
  protected $requiredPermission = false;
  /** @var mixed result of the action */
  protected $result;

  public function __construct() {
    $this->request        = new Request();
    $this->authentication = new Authentication();
    $this->output         = new Output();
    $this->authentication->checkUserPermission($this->requiredPermission, "controller");
    $this->userData = $this->authentication->getUserData();
  }

  /**
   * Run the action
   * @param string $action public action (method) name
   */
  public function action($action) {
    $result = $this->$action();
    $this->response($result, $this->request->post["json"]);
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