<?php

namespace jennifer\http;

use jennifer\sys\Globals;

/**
 * Class Request: present the http request object
 * @package jennifer\http
 */
class Request
{
    public $uri;
    public $get;
    public $post;
    public $headers;

    public function __construct()
    {
        $this->uri = Globals::server("REQUEST_URI");
        $this->get = Globals::get();
        $this->post = Globals::post();
        $this->headers = $this->getRequestHeaders();
    }

    /**
     * Get request headers
     * @return array
     */
    protected function getRequestHeaders()
    {
        $headers = [];
        foreach (Globals::server() as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }

        return $headers;
    }

    /**
     * Check if a post has been sent
     * @return bool
     */
    public function posted()
    {
        if (empty($this->post)) {
            return false;
        }

        return true;
    }

    /**
     * Check if post para exists then return value, else return false
     * @param $name
     * @return bool|mixed
     */
    public function hasPost($name)
    {
        return isset($this->post[$name]) ? $this->post[$name] : false;
    }

    /**
     * Check if post para exists then return value, else return false
     * @param $name
     * @return bool|mixed
     */
    public function hasGet($name)
    {
        return isset($this->get[$name]) ? $this->get[$name] : false;
    }

    /**
     * Check if post para exists then return value, else return false
     * @param $name
     * @return bool|mixed
     */
    public function hasHeader($name)
    {
        return isset($this->headers[$name]) ? $this->headers[$name] : false;
    }
}