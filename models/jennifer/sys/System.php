<?php

  namespace jennifer\sys;

  use jennifer\controller\ControllerFactory;
  use jennifer\http\Request;
  use jennifer\view\ViewFactory;
  use jennifer\view\ViewInterface;

  /**
   * Class System: System utility static class: load views, controllers, do redirect
   * @package jennifer\sys
   */
  class System {
    /** @var Config */
    public $config;
    /** @var  ViewInterface */
    protected $viewFactory;
    /** @var  ControllerFactory */
    protected $controllerFactory;
    /** @var  Request */
    protected $request;
    protected $view;
    protected $controller;
    protected $action;
    protected $routing;

    public function __construct(Config $config = null, ViewFactory $viewFactory = null, ControllerFactory $controllerFactory = null) {
      $this->config = $config ? $config : new Config();
      $this->routing = $this->config->getRouting();
      $this->viewFactory = $viewFactory ? $viewFactory : new ViewFactory();
      $this->controllerFactory = $controllerFactory ? $controllerFactory : new ControllerFactory();
      $this->request = new Request();
    }

    /**
     * Render view
     */
    public function renderView() {
      if ($this->view) {
        $view = $this->viewFactory->createView($this->view);
        $view->prepare();
        $view->render();
      }
    }

    /**
     * Run the controller
     */
    public function runController() {
      if ($this->controller && $this->action) {
        $controller = $this->controllerFactory->createController($this->controller);
        $controller->action($this->action);
      }
    }

    /**
     * @return $this
     */
    public function loadView() {
      list($domain, $module, $view) = explode("/", $this->request->uri);
      // there is no view => get default view
      if (!$view) {
        $view = Config::DEFAULT_VIEW;
      }
      // module is not in module list => this is default module which does not require module name in uri
      if (!in_array($module, Config::MODULE_LIST)) {
        $view = $module;
        $module = Config::DEFAULT_MODULE;
      }
      $file = Config::VIEW_DIR . $module . "/" . $view . Config::VIEW_EXT;
      if (file_exists($file)) {
        $class = $module . "\\" . $view;
      } else {
        // no class file exists => get default module and default view
        $file = Config::VIEW_DIR . Config::DEFAULT_MODULE . "/" . Config::DEFAULT_VIEW . Config::VIEW_EXT;
        $class = Config::DEFAULT_MODULE . "\\" . Config::DEFAULT_VIEW;
      }

      require_once($file);
      $this->view = $class;

      return $this;
    }

    /**
     * @return $this
     */
    public function loadController() {
      $action = $this->request->post["action"];
      $controller = $this->request->post["controller"];
      $file = Config::CONTROLLER_DIR . $controller . Config::CONTROLLER_EXT;
      if (file_exists($file)) {
        $class = str_replace("/", "", Config::CONTROLLER_DIR) . "\\" . $controller;
        require_once($file);
        $this->action = $action;
        $this->controller = $class;
      }

      return $this;
    }

  }