<?php

namespace jennifer\api;

use jennifer\exception\RequestException;
use jennifer\http\Request;
use jennifer\io\Output;
use jennifer\jwt\JWT;
use jennifer\sys\Config;

/**
 * API gateway which will call requested service to perform action
 * then response output and log api request
 * @package jennifer\api
 */
class API
{
    /** @var Request */
    protected $request;
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

    const API_REQUEST_NAME = "req";

    public function __construct(ServiceMap $serviceMap = null, ServiceFactory $serviceFactory = null)
    {
        $this->mapper = $serviceMap ? $serviceMap : new ServiceMap();
        $this->factory = $serviceFactory ? $serviceFactory : new ServiceFactory();
        $this->output = new Output();
        $this->request = new Request();
    }

    /**
     * Run the api
     * Call request service to perform action then log and response
     */
    public function run()
    {
        $service = $this->factory->createService($this->serviceClass, $this->userData, $this->para);
        $result = $service->run($this->action);
        $this->log($result);
        $this->response($result, $this->para["json"]);
    }

    /**
     * Process api request
     * @return $this
     * @throws RequestException
     */
    public function processRequest()
    {
        $req = $this->getRequest();
        if ($req) {
            $json = json_decode($req, true);
            if (!isset($json["token"]) || !isset($json["service"]) || !isset($json["action"])) {
                throw new RequestException(RequestException::ERROR_MSG_INVALID_API_REQUEST, RequestException::ERROR_CODE_INVALID_API_REQUEST);
            }

            $this->token = $json["token"];
            $this->userData = (array)JWT::decode($this->token, Config::JWT_KEY_API, ['HS256']);
            if (!isset($this->userData["userID"]) || !isset($this->userData["permission"])) {
                throw new RequestException(RequestException::ERROR_MSG_INVALID_API_TOKEN, RequestException::ERROR_CODE_INVALID_API_TOKEN);
            }

            list($this->serviceClass, $this->action) = $this->mapper->map($json["service"], $json["action"]);
            if (!$this->serviceClass || !$this->action) {
                throw new RequestException(RequestException::ERROR_MSG_INVALID_SERVICE, RequestException::ERROR_CODE_INVALID_SERVICE);
            }
            $this->para = isset($json["para"]) ? $json["para"] : [];

            return $this;
        }
        throw new RequestException(RequestException::ERROR_MSG_INVALID_API_REQUEST, RequestException::ERROR_CODE_INVALID_API_REQUEST);
    }

    /**
     * Get request: first looking for post, then get
     * @return bool|mixed
     */
    protected function getRequest()
    {
        if ($this->request->hasPost(self::API_REQUEST_NAME)) {
            return $this->request->hasPost(self::API_REQUEST_NAME);
        }

        if ($this->request->hasGet(self::API_REQUEST_NAME)) {
            return $this->request->hasGet(self::API_REQUEST_NAME);
        }

    }

    /**
     * Controller response
     * @param array|string $data
     * @param bool $json
     * @param int $jsonOpt
     */
    protected function response($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES)
    {
        $this->output->ajax($data, $json, $jsonOpt);
    }

    /**
     * Log user api request
     * @param array $result
     */
    protected function log($result = [])
    {
        $this->token;
        $this->userData;
        $this->serviceClass;
        $this->action;
        $this->para;
        $date = date("Y-m-d h:i:s");
    }
}