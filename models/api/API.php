<?php
/**
 * API gateway which will call requested service to perform action
 * then response output and log api request
 */
namespace api;

use auth\Authentication;
use io\Output;
use jwt\JWT;

class API {
  protected $mapper;
  protected $output;
  protected $hash;
  protected $service;
  protected $action;
  protected $userData = [];
  protected $para = [];
  public $messages = [
    "INVALID_API_REQUEST" => ["message" => "Invalid API request"],
    "INVALID_API_HASH"    => ["message" => "Invalid API authenticating hash"],
    "NO_SERVICE"          => ["message" => "Service not found"],
    "NO_SERVICE_MODEL"    => ["message" => "Service model not found"],
  ];

  public function __construct() {
    $this->mapper = new ServiceMap();
    $this->output = new Output();
  }

  /**
   * Run the api
   * Call request service to perform action then log and response
   */
  public function run() {
    /** @var  \api\ServiceInterface */
    $service = new $this->service($this->userData, $this->para) or die($this->messages["NO_SERVICE_MODEL"]["message"]);
    $action = $this->action;
    $result = $service->$action();
    $this->log($result);
    $this->response($result, $this->para["json"]);
  }

  /**
   * Process api request
   * @param string $req
   * @return $this
   */
  public function process($req) {
    $json = json_decode($req, true);
    if (!isset($json["hash"]) || !isset($json["service"]) || !isset($json["action"])) {
      die($this->messages["INVALID_API_REQUEST"]["message"]);
    }

    $this->hash     = $json["hash"];
    $this->userData = (array)JWT::decode($this->hash, Authentication::JWT_KEY_API, ['HS256']);
    if (!isset($this->userData["userID"]) || !isset($this->userData["permission"])) {
      die($this->messages["INVALID_API_HASH"]["message"]);
    }

    list($this->service, $this->action) = $this->mapper->map($json["service"], $json["action"]);
    if (!$this->service || !$this->action) {
      die($this->messages["NO_SERVICE"]["message"]);
    }

    $this->para = isset($json["para"]) ? $json["para"] : [];

    return $this;
  }

  /**
   * Controller response
   * @param array|string $data
   * @param bool $json
   * @param int $jsonOpt
   */
  protected function response($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES) {
    $this->output->ajax($data, $json, $jsonOpt);
  }

  /**
   * Log user api request
   * @param array $result
   */
  protected function log($result = []) {
    $this->hash;
    $this->userData;
    $this->service;
    $this->action;
    $this->para;
    $date = date("Y-m-d h:i:s");
  }
}