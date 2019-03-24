<?php

namespace Jennifer\Api;

use Jennifer\Api\Exception\ApiException;
use Jennifer\Api\Exception\ServiceException;
use Jennifer\Http\Request;
use Jennifer\IO\Output;
use Jennifer\Jwt\JWT;
use Jennifer\Sys\Config;
use Jennifer\Sys\Exception\ConfigException;

/**
 * API gateway which will call requested service to perform action
 * then response output and log api request
 * @package Jennifer\Api
 */
class Api implements ApiInterface
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
     * @param \Jennifer\Api\ServiceMapInterface|null $serviceMapper
     * @param \Jennifer\Api\ServiceFactory|null $serviceFactory
     * @param \Jennifer\IO\Output|null $output
     * @param \Jennifer\Http\Request|null $request
     * @throws \Jennifer\Sys\Exception\ConfigException
     */
    public function __construct(ServiceMapInterface $serviceMapper = null, ServiceFactory $serviceFactory = null,
                                Output $output = null, Request $request = null)
    {
        if (!($serviceMapper instanceof ServiceMapInterface)) {
            throw new ConfigException(ConfigException::ERROR_MSG_MISSING_SERVICE_MAP);
        }
        $this->mapper = $serviceMapper;
        $this->factory = $serviceFactory ?: new ServiceFactory();
        $this->output = $output ?: new Output();
        $this->request = $request ?: new Request();
    }

    /**
     * Run the api
     * Call request service to perform action then log and response
     * @throws \Jennifer\Api\Exception\ServiceException
     */
    public function run()
    {
        try {
            $service = $this->factory->createService($this->serviceClass, $this->userData, $this->para);
            $result = $service->run($this->action);
            $this->output->ajax($result, $this->para["json"]);
        } catch (ServiceException $exception) {
            throw $exception;
        }
    }

    /**
     * Process api request
     * @return $this
     * @throws \Jennifer\Api\Exception\ApiException
     * @throws \Jennifer\Api\Exception\ServiceException
     */
    public function processRequest()
    {
        $req = $this->getRequest();
        if (!$req) {
            throw new ApiException(ApiException::ERROR_MSG_INVALID_API_REQUEST);
        }

        $json = json_decode($req, true);
        if (!isset($json["token"]) || !isset($json["service"]) || !isset($json["action"])) {
            throw new ApiException(ApiException::ERROR_MSG_INVALID_API_REQUEST);
        }

        $this->token = $json["token"];
        $this->userData = (array)JWT::decode($this->token, Config::getConfig("JWT_KEY_API"), ['HS256']);
        if (!isset($this->userData["userID"]) || !isset($this->userData["permission"])) {
            throw new ApiException(ApiException::ERROR_MSG_INVALID_API_TOKEN);
        }

        list($this->serviceClass, $this->action) = $this->mapper->map($json["service"], $json["action"]);
        if (!$this->serviceClass || !$this->action) {
            throw new ServiceException(ServiceException::ERROR_MSG_INVALID_SERVICE);
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
     * @return \Jennifer\IO\Output
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param \Jennifer\IO\Output $output
     * @return $this
     */
    public function setOutput(Output $output)
    {
        $this->output = $output;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken(string $token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction(string $action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return array
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * @param array $userData
     * @return $this
     */
    public function setUserData(array $userData)
    {
        $this->userData = $userData;
        return $this;
    }

    /**
     * @return array
     */
    public function getPara()
    {
        return $this->para;
    }

    /**
     * @param array $para
     * @return $this
     */
    public function setPara(array $para)
    {
        $this->para = $para;
        return $this;
    }


}