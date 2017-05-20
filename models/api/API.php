<?php
  namespace api;

  use sys\System;

  class API {
    protected $get = [];
    protected $messages = [
      "NO_PERMISSION" => ["message" => "You are not authenticated to access API."],
    ];

    public function __construct() {
      $this->authenticate();
      $this->get = System::getGET();
    }

    /**
     * Check authentication of the request
     * - check for hash provided by the request
     * - check for hash of each action
     */
    protected function authenticate(){

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