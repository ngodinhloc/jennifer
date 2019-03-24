<?php

namespace Jennifer\Controller;

use Jennifer\Auth\Authentication;
use Jennifer\Controller\Exception\ControllerException;
use Jennifer\Http\Request;
use Jennifer\IO\Output;

/**
 * The base controller class: all controllers will extend this class
 * Each public function of controller class is an action
 * @package Jennifer\Controller
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

    /**
     * Controller constructor.
     * @param \Jennifer\Http\Request|null $request
     * @param \Jennifer\IO\Output|null $output
     * @throws \Jennifer\Controller\Exception\ControllerException
     */
    public function __construct(Request $request = null, Output $output = null)
    {
        $this->request = $request ?: new Request();
        $this->output = $output ?: new Output();
        $this->authentication = new Authentication();
        try {
            $this->authentication->checkUserPermission($this->requiredPermission, "controller");
        } catch (ControllerException $exception) {
            throw $exception;
        }
        $this->userData = $this->authentication->getUserData();
    }

    /**
     * Run the action
     * @param string $action public action (method) name
     * @throws \Jennifer\Controller\Exception\ControllerException
     */
    public function action($action)
    {
        if (method_exists($this, $action)) {
            $result = $this->$action();
            if ($result === false) {
                throw new ControllerException(ControllerException::ERROR_MSG_FAILED_TO_PERFORM_ACTION);
            }
            $this->output->ajax($result, $this->request->post["json"]);
        }

        throw new ControllerException(ControllerException::ERROR_MSG_NO_CONTROLLER_ACTION);
    }

    /**
     * @return \Jennifer\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return \Jennifer\Controller\Controller
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return \Jennifer\IO\Output
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param \Jennifer\IO\Output $output
     * @return \Jennifer\Controller\Controller
     */
    public function setOutput(Output $output)
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
     * @return \Jennifer\Controller\Controller
     */
    public function setUserData($userData)
    {
        $this->userData = $userData;
        return $this;
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
}