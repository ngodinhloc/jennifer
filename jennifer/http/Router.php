<?php

namespace jennifer\http;

use jennifer\exception\ConfigException;
use jennifer\exception\RequestException;
use jennifer\sys\Globals;

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
                throw new ConfigException(ConfigException::ERROR_MSG_MISSING_ROUTE_FILE, ConfigException::ERROR_CODE_MISSING_ROUTE_FILE);
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
     * @throws RequestException
     */
    public function loadController($action, $controller)
    {
        $file = Globals::docRoot() . "/" . getenv("CONTROLLER_DIR") . $controller . ".php";
        if (!file_exists($file)) {
            throw new RequestException(RequestException::ERROR_MSG_INVALID_CONTROLLER, RequestException::ERROR_CODE_INVALID_CONTROLLER);
        }
        require_once($file);
        $controller = str_replace("/", "\\", $controller);

        return [$action, $controller];
    }

    /**
     * @param $uri
     * @return string|bool
     * @throws RequestException
     */
    public function loadView($uri)
    {
        if ($url = $this->getRoute($uri)) {
            $route = $this->routes[$url];
            $file = Globals::docRoot() . "/" . getenv("VIEW_DIR") . $route . ".php";
            if (!file_exists($file)) {
                throw new RequestException(RequestException::ERROR_MSG_INVALID_VIEW, RequestException::ERROR_CODE_INVALID_VIEW);
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
     * @throws RequestException
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

        throw new RequestException(RequestException::ERROR_MSG_INVALID_ROUTE, RequestException::ERROR_CODE_INVALID_ROUTE);
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