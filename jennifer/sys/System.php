<?php

namespace jennifer\sys;

use jennifer\controller\ControllerFactory;
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
    
    const MSG_ROUTE_NOT_FOUND      = "Route not found.";
    const MSG_VIEW_NOT_FOUND       = "View not found.";
    const MSG_CONTROLLER_NOT_FOUND = "Controller not found";
    
    public function __construct($configFiles = [], $routeFiles = []) {
        $this->config            = new Config($configFiles);
        $this->router            = new Router($routeFiles);
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
            }
            catch (RequestException $exception) {
                throw $exception;
            }
            $controller->action($this->action);
        }
    }
    
    /**
     * @return $this
     */
    public function loadView() {
        if ($this->view = $this->router->getView($this->request->uri)) {
            return $this;
        }
    
        $this->response->error(self::MSG_VIEW_NOT_FOUND);
    }
    
    /**
     * @return $this
     */
    public function matchRoute() {
        if ($this->route = $this->router->getRoute($this->request->uri)) {
            return $this;
        }
        
        $this->response->error(self::MSG_ROUTE_NOT_FOUND);
    }
    
    /**
     * @return $this
     */
    public function loadController() {
        $action     = $this->request->post["action"];
        $controller = $this->request->post["controller"];
        $file       = Globals::docRoot() . "/" . getenv("CONTROLLER_DIR") . $controller . ".php";
        
        if (file_exists($file)) {
            $class = str_replace("/", "", getenv("CONTROLLER_DIR") . "\\" . $controller);
            require_once($file);
            $this->action     = $action;
            $this->controller = $class;
    
            return $this;
        }
    
        $this->response->error(self::MSG_CONTROLLER_NOT_FOUND);
    }
    
}