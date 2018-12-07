<?php

namespace jennifer\sys;

use jennifer\api\APIInterface;
use jennifer\controller\ControllerFactory;
use jennifer\controller\ControllerInterface;
use jennifer\exception\ConfigException;
use jennifer\exception\RequestException;
use jennifer\http\Request;
use jennifer\http\Response;
use jennifer\http\Router;
use jennifer\view\Base;
use jennifer\view\ViewFactory;
use jennifer\view\ViewInterface;

/**
 * Class System: System utility static class: load views, controllers, do redirect
 * @package jennifer\sys
 */
class System
{
    /** @var Config */
    protected $config;
    /** @var Router */
    protected $router;
    /** @var  Request */
    protected $request;
    /** @var Response */
    protected $response;
    /** @var  ViewInterface */
    protected $viewFactory;
    /** @var  ControllerFactory */
    protected $controllerFactory;
    /** @var APIInterface */
    protected $api;

    protected $viewRoute;
    protected $viewClass;
    protected $controllerClass;
    protected $controllerAction;


    /**
     * System constructor.
     * @param array $configFiles
     * @throws ConfigException
     */
    public function __construct(array $configFiles)
    {
        try {
            $this->config = new Config($configFiles);
        } catch (ConfigException $exception) {
            throw $exception;
        }
        $this->request = new Request();
        $this->response = new Response();
        $this->viewFactory = new ViewFactory();
        $this->controllerFactory = new ControllerFactory();
    }

    /**
     * @return System
     * @throws RequestException
     * @throws ConfigException
     */
    public function loadView()
    {
        if (!$this->router) {
            throw new ConfigException(ConfigException::ERROR_MSG_MISSING_ROUTER, ConfigException::ERROR_CODE_MISSING_ROUTER);
        }
        try {
            $this->viewRoute = $this->router->getRoute($this->request->uri);
            $this->viewClass = $this->router->loadView($this->request->uri);
        } catch (RequestException $exception) {
            throw $exception;
        }

        return $this;
    }

    /**
     * Render view
     * @throws RequestException
     */
    public function renderView()
    {
        if (!$this->viewRoute || !$this->viewClass) {
            throw new RequestException(RequestException::ERROR_MSG_INVALID_VIEW, RequestException::ERROR_CODE_INVALID_VIEW);
        }
        try {
            /** @var ViewInterface|Base $view */
            $view = $this->viewFactory->createView($this->viewClass);
            $view->setRoute($this->viewRoute)->processPara();
            $view->prepare()->render();
        } catch (RequestException $exception) {
            throw $exception;
        }
    }

    /**
     * @return System
     * @throws RequestException
     */
    public function loadController()
    {
        try {
            list($this->controllerAction, $this->controllerClass) = $this->router->loadController($this->request->post["action"], $this->request->post["controller"]);
            return $this;
        } catch (RequestException $exception) {
            throw $exception;
        }
    }

    /**
     * Run the controller
     * @throws RequestException
     */
    public function runController()
    {
        if (!$this->controllerClass || !$this->controllerAction) {
            throw new RequestException(RequestException::ERROR_MSG_INVALID_CONTROLLER, RequestException::ERROR_CODE_INVALID_CONTROLLER);
        }
        try {
            /** @var ControllerInterface $controller */
            $controller = $this->controllerFactory->createController($this->controllerClass);
            $controller->action($this->controllerAction);
        } catch (RequestException $exception) {
            throw $exception;
        }
    }

    /**
     * @throws RequestException
     * @throws ConfigException
     */
    public function runAPI()
    {
        if (!$this->api) {
            throw new ConfigException(ConfigException::ERROR_MSG_MISSING_API, ConfigException::ERROR_CODE_MISSING_API);
        }
        try {
            $this->api->processRequest()->run();
        } catch (RequestException $exception) {
            throw $exception;
        }
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param Router $router
     * @return System
     * @throws ConfigException;
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
        $this->router->loadRoutes();
        return $this;
    }

    /**
     * @return APIInterface
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param APIInterface $api
     * @return System
     */
    public function setApi(APIInterface $api)
    {
        $this->api = $api;
        return $this;
    }
}