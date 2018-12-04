<?php

namespace jennifer\http;
use back\about;
use jennifer\sys\Config;

class Router {
    private $routes;
    
    public function __construct($routes = []) {
        $this->routes = $routes;
    }
    
    /**
     * @param $uri
     * @return string|bool
     */
    public function getRoute($uri) {
        if ($route = $this->matchRoute($uri)) {
            $file = DOC_ROOT . "/" . Config::VIEW_DIR . $route . Config::VIEW_EXT;
            if (file_exists($file)) {
                $class = str_replace("/", "\\", $route);
                require_once($file);
                
                return $class;
            }
        }
        
        return false;
    }
    
    /**
     * @param $uri
     * @return bool|mixed
     */
    public function matchRoute($uri) {
        if ($uri == "/") {
            if (isset($this->routes[$uri])) {
                return $this->routes[$uri];
            }
        }
        foreach ($this->routes as $url => $route) {
            if ($url != "/" && strpos($uri, $url) === 0) {
                
                return $route;
            }
        }
        
        return false;
    }
    
    /**
     * @return array
     */
    public function getRoutes() {
        return $this->routes;
    }
    
    /**
     * @param array $routes
     * ["uri" => class]
     * @return Router
     */
    public function setRoutes($routes) {
        $this->routes = $routes;
        
        return $this;
    }
    
}