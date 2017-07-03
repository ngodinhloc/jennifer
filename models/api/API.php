<?php
  namespace api;

  use sys\System;
  use thedaysoflife\User;

  class API {
    protected $para = [];
    protected $messages = [
      "NO_PERMISSION" => ["message" => "You are not authenticated to access API."],
    ];

    public function __construct() {
      $this->authenticate();
      $this->para = System::getGET();
    }

    /*
     * Get day by id
     * Required para: id
     * Sample api request: http://www.thedaysoflife.com/api/?action=getDay&id=100151&json=1
     */
    public function getDay() {
      $id = $this->hasPara("id");
      $json = $this->hasPara("json") == 1 ? true : false;
      if ($id) {
        $user = new User();
        $day = $user->getDayById($id);
        $this->response($day, $json);
      }
    }

    /**
     * Check if get para exists then return value, else return false
     * @param $name
     * @return bool|mixed
     */
    public function hasPara($name) {
      return isset($this->para[$name]) ? $this->para[$name] : false;
    }

    /**
     * Check authentication of the request
     * - check for hash provided by the request
     * - check for hash of each action
     */
    protected function authenticate() {
      return true;
    }

    /**
     * Controller response
     * @param array|string $data
     * @param bool $json
     * @param int $jsonOpt
     */
    public function response($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES) {
      if (is_array($data)) {
        if ($json) {
          header('Content-Type: application/json');
          echo(json_encode($data, $jsonOpt));
          exit;
        }
        echo(json_encode($data, $jsonOpt));
        exit;
      }
      echo $data;
      exit;
    }
  }