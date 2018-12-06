<?php

namespace jennifer\sys;

use jennifer\controller\ControllerFactory;
use jennifer\exception\ConfigException;
use jennifer\http\Request;
use jennifer\http\Response;
use jennifer\http\Router;
use jennifer\view\Base;
use jennifer\view\ViewFactory;
use jennifer\view\ViewInterface;
use jennifer\exception\RequestException;

/**
 * Class System: System utility static class: load views, controllers, do redirect
 * @package jennifer\sys
 */
class System {
    /** @var Config */
    protected $config;
    /** @var Router */
    protected $router;
    /** @var  ViewInterface */
    protected $viewFactory;
    /** @var  ControllerFactory */
    protected $controllerFactory;
    /** @var  Request */
    protected $request;
    /** @var Response */
    protected $response;
    protected $route;
    protected $view;
    protected $controller;
    protected $action;
    
    /**
     * System constructor.
     * @param array $configFiles
     * @param array $routeFiles
     * @throws ConfigException
     */
    public function __construct($configFiles = [], $routeFiles = []) {
        try {
            $this->config = new Config($configFiles);
            $this->router = new Router($routeFiles);
        }
        catch (ConfigException $exception) {
            throw $exception;
        }
        $this->request           = new Request();
        $this->response          = new Response();
        $this->viewFactory       = new ViewFactory();
        $this->controllerFactory = new ControllerFactory();
    }
    
    /**
     * Render view
     * @throws RequestException
     */
    public function renderView() {
        if ($this->view) {
            try {
                /** @var ViewInterface|Base $view */
                $view = $this->viewFactory->createView($this->view);
                $view->setRoute($this->route)->processPara();
            }
            catch (RequestException $exception) {
                throw $exception;
            }
            $view->prepare();
            $view->render();
        }
    }
    
    /**
     * Run the controller
     * @throws RequestException
     */
    public function runController() {
        if ($this->controller && $this->action) {
            try {
                $controller = $this->controllerFactory->createController($this->controller);
                $controller->action($this->action);
            }
            catch (RequestException $exception) {
                throw $exception;
            }
        }
    }
    
    /**
     * @return $this
     * @throws RequestException
     */
    public function loadView() {
        try {
            $this->view = $this->router->getView($this->request->uri);
        }
        catch (RequestException $exception) {
            throw $exception;
        }
    
        return $this;
    }
    
    /**
     * @return $this
     * @throws RequestException
     */
    public function matchRoute() {
        try {
            $this->route = $this->router->getRoute($this->request->uri);
        }
        catch (RequestException $exception) {
            throw $exception;
        }
    
        return $this;
    }
    
    /**
     * @return $this
     * @throws RequestException
     */
    public function loadController() {
        $action     = $this->request->post["action"];
        $controller = $this->request->post["controller"];
        $file       = Globals::docRoot() . "/" . getenv("CONTROLLER_DIR") . $controller . ".php";
        if (!file_exists($file)) {
            throw new RequestException(RequestException::ERROR_MSG_INVALID_CONTROLLER, RequestException::ERROR_CODE_INVALID_CONTROLLER);
        }
        require_once($file);
        $class            = str_replace("/", "", getenv("CONTROLLER_DIR") . "\\" . $controller);
        $this->action     = $action;
        $this->controller = $class;
    
        return $this;
    }
    
}