<?php
namespace jennifer\api;

use jennifer\auth\Authentication;
use jennifer\io\Output;
use jennifer\jwt\JWT;

/**
 * API gateway which will call requested service to perform action
 * then response output and log api request
 * @package api
 */
class API {
  /** @var  \jennifer\api\ServiceFactory */
  protected $factory;
  /** @var ServiceMap mapper to map from requested service to api service */
  protected $mapper;
  /** @var Output output object */
  protected $output;
  /** @var  string authentication token */
  protected $token;
  /** @var  string requested service */
  protected $serviceClass;
  /** @var  string requested action */
  protected $action;
  /** @var array user data */
  protected $userData = [];
  /** @var array parameters */
  protected $para = [];
  /** @var array output message */
  public $messages = [
    "INVALID_API_REQUEST" => ["message" => "Invalid API request"],
    "INVALID_API_TOKEN"   => ["message" => "Invalid API authenticating token"],
    "NO_SERVICE"          => ["message" => "Service not found"],
    "NO_SERVICE_MODEL"    => ["message" => "Service model not found"],
  ];

  public function __construct() {
    $this->mapper  = new ServiceMap();
    $this->output  = new Output();
    $this->factory = new ServiceFactory();
  }

  /**
   * Run the api
   * Call request service to perform action then log and response
   */
  public function run() {
    $service = $this->factory->createService($this->serviceClass, $this->userData, $this->para);
    $result  = $service->run($this->action);
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
    if (!isset($json["token"]) || !isset($json["service"]) || !isset($json["action"])) {
      die($this->messages["INVALID_API_REQUEST"]["message"]);
    }

    $this->token    = $json["token"];
    $this->userData = (array)JWT::decode($this->token, Authentication::JWT_KEY_API, ['HS256']);
    if (!isset($this->userData["userID"]) || !isset($this->userData["permission"])) {
      die($this->messages["INVALID_API_TOKEN"]["message"]);
    }

    list($this->serviceClass, $this->action) = $this->mapper->map($json["service"], $json["action"]);
    if (!$this->serviceClass || !$this->action) {
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
    $this->token;
    $this->userData;
    $this->serviceClass;
    $this->action;
    $this->para;
    $date = date("Y-m-d h:i:s");
  }
}