<?php

namespace jennifer\sys;

use jennifer\controller\ControllerFactory;
use jennifer\http\Request;
use jennifer\http\Response;
use jennifer\http\Router;
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
    protected $view;
    protected $controller;
    protected $action;
    
    const MSG_ROUTE_NOT_FOUND = "Route not found.";
    
    public function __construct(Config $config = null, Request $request = null, ViewFactory $viewFactory = null,
                                ControllerFactory $controllerFactory = null) {
        $this->config            = $config ? $config : new Config();
        $this->router            = new Router($this->config->getRoutes());
        $this->request           = $request ? $request : new Request();
        $this->response          = new Response();
        $this->viewFactory       = $viewFactory ? $viewFactory : new ViewFactory();
        $this->controllerFactory = $controllerFactory ? $controllerFactory : new ControllerFactory();
    }
    
    /**
     * Render view
     * @throws RequestException
     */
    public function renderView() {
        if ($this->view) {
            try {
                $view = $this->viewFactory->createView($this->view);
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
        if ($class = $this->router->getRoute($this->request->uri)) {
            $this->view = $class;
            
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
        $file       = Config::CONTROLLER_DIR . $controller . Config::CONTROLLER_EXT;
        if (file_exists($file)) {
            $class = str_replace("/", "", Config::CONTROLLER_DIR) . "\\" . $controller;
            require_once($file);
            $this->action     = $action;
            $this->controller = $class;
        }
        
        return $this;
    }
    
}