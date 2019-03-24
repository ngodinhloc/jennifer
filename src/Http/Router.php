<?php

namespace Jennifer\Http;

use Jennifer\Controller\Exception\ControllerException;
use Jennifer\Sys\Config;
use Jennifer\Sys\Exception\ConfigException;
use Jennifer\Sys\Globals;
use Jennifer\View\Exception\ViewException;

class  Router
{
    protected $files = [];
    protected $routes;

    /**
     * Router constructor.
     * @param array $files
     */
    public function __construct($files = [])
    {
        $this->files = $files;
    }

    /**
     * Load route from config files
     * @throws ConfigException
     */
    public function loadRoutes()
    {
        foreach ($this->files as $file) {
            if (!file_exists($file)) {
                throw new ConfigException(ConfigException::ERROR_MSG_MISSING_ROUTE_FILE);
            }
            $routes = parse_ini_file($file, false);
            foreach ($routes as $url => $route) {
                $this->routes[$url] = $route;
            }
        }
    }

    /**
     * @param string $action
     * @param string $controller
     * @return array
     * @throws ControllerException
     */
    public function loadController($action, $controller)
    {
        $file = Globals::docRoot() . "/" . Config::getConfig("CONTROLLER_DIR") . $controller . ".php";
        if (!file_exists($file)) {
            throw new ControllerException(ControllerException::ERROR_MSG_INVALID_CONTROLLER . $controller);
        }
        require_once($file);
        $controller = str_replace("/", "\\", $controller);

        return [$action, $controller];
    }

    /**
     * @param $uri
     * @return string|bool
     * @throws ViewException
     */
    public function loadView($uri)
    {
        if ($url = $this->getRoute($uri)) {
            $route = $this->routes[$url];
            $file = Globals::docRoot() . "/" . Config::getConfig("VIEW_DIR") . $route . ".php";
            if (!file_exists($file)) {
                throw new ViewException(ViewException::ERROR_MSG_INVALID_VIEW);
            }
            $class = str_replace("/", "\\", $route);
            require_once($file);

            return $class;

        }

        return false;
    }

    /**
     * @param $uri
     * @return bool|mixed
     * @throws ViewException
     */
    public function getRoute($uri)
    {
        if ($uri == "/") {
            if (isset($this->routes[$uri])) {
                return $uri;
            }
        }
        foreach ($this->routes as $url => $route) {
            if ($url != "/" && strpos($uri, $url) === 0) {

                return $url;
            }
        }

        throw new ViewException(ViewException::ERROR_MSG_INVALID_ROUTE);
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param array $routes
     * ["uri" => class]
     * @return Router
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;

        return $this;
    }

}