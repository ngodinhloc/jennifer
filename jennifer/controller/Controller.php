<?php

namespace jennifer\controller;

use jennifer\auth\Authentication;
use jennifer\exception\RequestException;
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
    
    const ERROR_CODE_CONTROLLER_NOT_FOUND = 1;
    const ERROR_CODE_ACTION_NOT_FOUND     = 2;
    
    /**
     * Controller constructor.
     * @throws RequestException
     */
    public function __construct() {
        $this->request        = new Request();
        $this->authentication = new Authentication();
        $this->output         = new Output();
        try {
            $this->authentication->checkUserPermission($this->requiredPermission, "controller");
        }
        catch (RequestException $exception) {
            throw $exception;
        }
        $this->userData = $this->authentication->getUserData();
    }
    
    /**
     * Run the action
     * @param string $action public action (method) name
     * @throws RequestException
     */
    public function action($action) {
        if (method_exists($this, $action)) {
            $result = $this->$action();
            $this->output->ajax($result, $this->request->post["json"]);
        }
    
        throw new RequestException(RequestException::ERROR_MSG_NO_CONTROLLER_ACTION, RequestException::ERROR_CODE_NO_CONTROLLER_ACTION);
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