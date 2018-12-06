<?php

namespace jennifer\http;
use jennifer\sys\Globals;

class Router {
    private $files = [];
    private $routes;
    
    public function __construct($files = []) {
        $this->files = $files;
        $this->loadRoutes();
    }
    
    /**
     * Load route from config files
     */
    public function loadRoutes() {
        foreach ($this->files as $file) {
            $routes = parse_ini_file($file, false);
            foreach ($routes as $url => $route) {
                $this->routes[$url] = $route;
            }
        }
    }
    
    /**
     * @param $uri
     * @return string|bool
     */
    public function getView($uri) {
        if ($url = $this->getRoute($uri)) {
            $route = $this->routes[$url];
            $file  = Globals::docRoot() . "/" . getenv("VIEW_DIR") . $route . ".php";
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
    public function getRoute($uri) {
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