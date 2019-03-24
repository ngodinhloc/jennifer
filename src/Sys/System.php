<?php

namespace Jennifer\Sys;

use Jennifer\Api\ApiInterface;
use Jennifer\Controller\ControllerFactory;
use Jennifer\Controller\ControllerInterface;
use Jennifer\Controller\Exception\ControllerException;
use Jennifer\Http\Exception\RequestException;
use Jennifer\Http\Request;
use Jennifer\Http\Response;
use Jennifer\Http\Router;
use Jennifer\Sys\Exception\ConfigException;
use Jennifer\View\Base;
use Jennifer\View\Exception\ViewException;
use Jennifer\View\ViewFactory;
use Jennifer\View\ViewInterface;

/**
 * Class System: System utility static class: load views, controllers, do redirect
 * @package Jennifer\Sys
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
    /** @var ApiInterface */
    protected $api;

    protected $viewRoute;
    protected $viewClass;
    protected $controllerClass;
    protected $controllerAction;


    /**
     * System constructor.
     * @param array $configFiles
     * @throws \Jennifer\Sys\Exception\ConfigException
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
     * @return \Jennifer\Sys\System
     * @throws \Jennifer\View\Exception\ViewException
     * @throws \Jennifer\Sys\Exception\ConfigException
     */
    public function loadView()
    {
        if (!$this->router) {
            throw new ConfigException(ConfigException::ERROR_MSG_MISSING_ROUTER);
        }
        try {
            $this->viewRoute = $this->router->getRoute($this->request->uri);
            $this->viewClass = $this->router->loadView($this->request->uri);
        } catch (ViewException $exception) {
            throw $exception;
        }

        return $this;
    }

    /**
     * Render view
     * @throws \Jennifer\View\Exception\ViewException
     */
    public function renderView()
    {
        if (!$this->viewRoute || !$this->viewClass) {
            throw new ViewException(ViewException::ERROR_MSG_INVALID_VIEW);
        }
        try {
            /** @var ViewInterface|Base $view */
            $view = $this->viewFactory->createView($this->viewClass);
            $view->setRoute($this->viewRoute)->processPara();
            $view->prepare()->render();
        } catch (ViewException $exception) {
            throw $exception;
        }
    }

    /**
     * @return \Jennifer\Sys\System
     * @throws \Jennifer\Controller\Exception\ControllerException
     */
    public function loadController()
    {
        try {
            list($this->controllerAction, $this->controllerClass) = $this->router->loadController($this->request->post["action"], $this->request->post["controller"]);
            return $this;
        } catch (ControllerException $exception) {
            throw $exception;
        }
    }

    /**
     * Run the controller
     * @throws \Jennifer\Controller\Exception\ControllerException
     */
    public function runController()
    {
        if (!$this->controllerClass || !$this->controllerAction) {
            throw new ControllerException(ControllerException::ERROR_MSG_INVALID_CONTROLLER);
        }
        try {
            /** @var ControllerInterface $controller */
            $controller = $this->controllerFactory->createController($this->controllerClass);
            $controller->action($this->controllerAction);
        } catch (ControllerException $exception) {
            throw $exception;
        }
    }

    /**
     * @throws \Jennifer\Http\Exception\RequestException
     * @throws \Jennifer\Sys\Exception\ConfigException
     */
    public function runAPI()
    {
        if (!$this->api) {
            throw new ConfigException(ConfigException::ERROR_MSG_MISSING_API);
        }
        try {
            $this->api->processRequest()->run();
        } catch (RequestException $exception) {
            throw $exception;
        }
    }

    /**
     * @return \Jennifer\Http\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param \Jennifer\Http\Router $router
     * @return \Jennifer\Sys\System
     * @throws \Jennifer\Sys\Exception\ConfigException;
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
        $this->router->loadRoutes();
        return $this;
    }

    /**
     * @return \Jennifer\Api\ApiInterface
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param \Jennifer\Api\ApiInterface $api
     * @return \Jennifer\Sys\System
     */
    public function setApi(ApiInterface $api)
    {
        $this->api = $api;
        return $this;
    }
}