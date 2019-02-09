<?php

namespace jennifer\api;

use jennifer\exception\ConfigException;
use jennifer\exception\RequestException;
use jennifer\http\Request;
use jennifer\io\Output;
use jennifer\jwt\JWT;
use jennifer\sys\Config;
use thedaysoflife\api\ServiceMapper;

/**
 * API gateway which will call requested service to perform action
 * then response output and log api request
 * @package jennifer\api
 */
class API implements APIInterface
{
    /** @var Request */
    protected $request;
    /** @var  ServiceFactory */
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

    /**
     * API constructor.
     * @param ServiceMap|null $serviceMapper
     * @param ServiceFactory|null $serviceFactory
     * @param Output|null $output
     * @param Request|null $request
     * @throws ConfigException
     */
    public function __construct(ServiceMap $serviceMapper = null, ServiceFactory $serviceFactory = null,
                                Output $output = null, Request $request = null)
    {
        if (!($serviceMapper instanceof ServiceMap)) {
            throw new ConfigException(ConfigException::ERROR_MSG_MISSING_SERVICE_MAP, ConfigException::ERROR_CODE_MISSING_SERVICE_MAP);
        }
        $this->mapper = $serviceMapper ?: new ServiceMapper();
        $this->factory = $serviceFactory ?: new ServiceFactory();
        $this->output = $output ?: new Output();
        $this->request = $request ?: new Request();
    }

    /**
     * Run the api
     * Call request service to perform action then log and response
     * @throws RequestException
     */
    public function run()
    {
        try {
            $service = $this->factory->createService($this->serviceClass, $this->userData, $this->para);
            $result = $service->run($this->action);
            $this->output->ajax($result, $this->para["json"]);
        } catch (RequestException $exception) {
            throw $exception;
        }
    }

    /**
     * Process api request
     * @return $this
     * @throws RequestException
     */
    public function processRequest()
    {
        $req = $this->getRequest();
        if (!$req) {
            throw new RequestException(RequestException::ERROR_MSG_INVALID_API_REQUEST, RequestException::ERROR_CODE_INVALID_API_REQUEST);
        }

        $json = json_decode($req, true);
        if (!isset($json["token"]) || !isset($json["service"]) || !isset($json["action"])) {
            throw new RequestException(RequestException::ERROR_MSG_INVALID_API_REQUEST, RequestException::ERROR_CODE_INVALID_API_REQUEST);
        }

        $this->token = $json["token"];
        $this->userData = (array)JWT::decode($this->token, Config::getConfig("JWT_KEY_API"), ['HS256']);
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

        return false;
    }

    /**
     * @return Output
     */
    public function getOutput(): Output
    {
        return $this->output;
    }

    /**
     * @param Output $output
     * @return API
     */
    public function setOutput(Output $output): API
    {
        $this->output = $output;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return API
     */
    public function setToken(string $token): API
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return API
     */
    public function setAction(string $action): API
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return array
     */
    public function getUserData(): array
    {
        return $this->userData;
    }

    /**
     * @param array $userData
     * @return API
     */
    public function setUserData(array $userData): API
    {
        $this->userData = $userData;
        return $this;
    }

    /**
     * @return array
     */
    public function getPara(): array
    {
        return $this->para;
    }

    /**
     * @param array $para
     * @return API
     */
    public function setPara(array $para): API
    {
        $this->para = $para;
        return $this;
    }


}