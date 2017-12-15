<?php

  namespace jennifer\http;

  use jennifer\sys\Globals;

  class Request {
    protected $uri;
    protected $get;
    protected $post;

    public function __construct() {
      $this->uri = Globals::server("REQUEST_URI");
      $this->get = Globals::get();
      $this->post = Globals::post();
    }

    /**
     * @return bool|string
     */
    public function getURI() {
      return $this->uri;
    }

    /**
     * @return array|bool
     */
    public function getToPara() {
      if (empty($this->get)) {
        return false;
      }

      $para = [];
      foreach ($this->get as $name => $value) {
        $para[$name] = $value;
      }

      return $para;
    }

    /**
     * @return array|bool
     */
    public function postToPara() {
      if (empty($this->post)) {
        return false;
      }

      $para = [];
      foreach ($this->post as $key => $value) {
        $para[$key] = $value;
      }

      return $para;
    }
  }