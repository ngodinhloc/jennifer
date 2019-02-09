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
class Controller implements ControllerInterface
{
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
    const ERROR_CODE_ACTION_NOT_FOUND = 2;

    /**
     * Controller constructor.
     * @param Request|null $request
     * @param Output|null $output
     * @throws RequestException
     */
    public function __construct(Request $request = null, Output $output = null)
    {
        $this->request = $request ?: new Request();
        $this->output = $output ?: new Output();
        $this->authentication = new Authentication();
        try {
            $this->authentication->checkUserPermission($this->requiredPermission, "controller");
        } catch (RequestException $exception) {
            throw $exception;
        }
        $this->userData = $this->authentication->getUserData();
    }

    /**
     * Run the action
     * @param string $action public action (method) name
     * @throws RequestException
     */
    public function action($action)
    {
        if (method_exists($this, $action)) {
            $result = $this->$action();
            $this->output->ajax($result, $this->request->post["json"]);
        }

        throw new RequestException(RequestException::ERROR_MSG_NO_CONTROLLER_ACTION, RequestException::ERROR_CODE_NO_CONTROLLER_ACTION);
    }

    /**
     * Get the required permissions for controller
     */
    protected function getRequiredPermission()
    {
        return $this->requiredPermission;
    }

    /**
     * Load required permission from database or set required permission on each controller
     */
    protected function loadRequiredPermission()
    {

    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return Controller
     */
    public function setRequest(Request $request): Controller
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return Output
     */
    public function getOutput(): Output
    {
        return $this->output;
    }

    /**
     * @param Output $output
     * @return Controller
     */
    public function setOutput(Output $output): Controller
    {
        $this->output = $output;
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * @param array|bool $userData
     * @return Controller
     */
    public function setUserData($userData)
    {
        $this->userData = $userData;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     * @return Controller
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }
}