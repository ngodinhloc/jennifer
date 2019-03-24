<?php

namespace Jennifer\Auth;

use Jennifer\Api\Exception\ServiceException;
use Jennifer\Controller\Exception\ControllerException;
use Jennifer\Http\Response;
use Jennifer\Jwt\JWT;
use Jennifer\Sys\Config;
use Jennifer\Sys\Globals;

/**
 * Class Authentication: responsible for checking user permission, api permission
 * @package Jennifer\Auth
 */
class Authentication implements AuthenticationInterface
{
    /** @var array output message */
    public $messages = [
        "NO_PERMISSION_VIEW" => ["message" => "You do not have permission to access this view."],
        "NO_PERMISSION_CONTROLLER" => ["message" => "You do not have permission to access this controller."],
        "NO_PERMISSION_API" => ["message" => "You do not have permission to access this API."],
        "NO_AUTHENTICATION" => ["message" => "The view/controller you tried to access requires authentication."],
        "INVALID_AUTHENTICATION" => ["message" => "Incorrect username or password."],
        "USER_STATUS_DISABLE" => ["message" => "This user is disabled."],
    ];
    /** @var Response */
    protected $response;
    /** @var bool|object */
    protected $userData = false;
    const AUTH_TYPE_USER = 1;
    const AUTH_TYPE_API = 2;
    const USER_STATUS_ACTIVE = 'active';
    const USER_STATUS_DISABLE = 'disable';

    /**
     * Authentication constructor.
     * @param int $type
     * @param \Jennifer\Http\Response|null $response
     */
    public function __construct($type = self::AUTH_TYPE_USER, Response $response = null)
    {
        if ($type == self::AUTH_TYPE_USER) {
            $this->userData = $this->getJWT();
        }
        $this->response = $response ? $response : new Response();
    }

    /**
     * Check jwt in session
     * @return object|bool
     */
    public function getJWT()
    {
        $jwt = Globals::session("jwt");
        if ($jwt) {
            $decoded = JWT::decode($jwt, Config::getConfig("JWT_KEY_USER"), ['HS256']);
            if (isset($decoded->id) && isset($decoded->name) && isset($decoded->permission)) {
                return $decoded;
            }
        }

        return false;
    }

    /**
     *  Check service permission
     * @param bool|array $userPermission
     * @param bool|array $requiredPermission
     * @return bool
     * @throws \Jennifer\Api\Exception\ServiceException
     */
    public function checkServicePermission($userPermission = false, $requiredPermission = false)
    {
        // no permission required
        if (!$requiredPermission) {
            return true;
        }

        // user has no permission
        if (!$userPermission) {
            throw new ServiceException(ServiceException::ERROR_MSG_NO_SERVICE_PERMISSION);
        }

        // check each required permission against user permission
        foreach ($requiredPermission as $per) {
            if (!in_array($per, $userPermission)) {
                throw new ServiceException(ServiceException::ERROR_MSG_NO_SERVICE_PERMISSION);
            }
        }

        return true;
    }

    /**
     * Check user permission against permission list
     * @param array|boolean $requiredPermission
     * @param string $checkType
     * @return bool
     * @throws \Jennifer\Controller\Exception\ControllerException
     */
    public function checkUserPermission($requiredPermission = false, $checkType = "view")
    {
        // no permission required
        if (!$requiredPermission) {
            return true;
        }

        $userPermission = $this->getUserPermission();
        // if user has no permission data
        if (!$userPermission) {
            $this->handleNoPermission($checkType);

        }

        // check each required permission against user permission
        foreach ($requiredPermission as $per) {
            if (!in_array($per, $userPermission)) {
                $this->handleNoPermission($checkType);
            }
        }

        return true;
    }

    /**
     * Get user permission
     * @return array|boolean
     */
    public function getUserPermission()
    {
        if ($this->userData) {
            return explode(",", $this->userData->permission);
        }

        return false;
    }

    /**
     * Handle user with no permission
     * @param string $checkType
     * @throws \Jennifer\Controller\Exception\ControllerException
     */
    protected function handleNoPermission($checkType)
    {
        switch ($checkType) {
            case "view":
                $this->response->redirect(Config::getConfig("LOGIN_VIEW_URL"), $this->messages["NO_PERMISSION_VIEW"]);
                break;
            case "controller":
                throw new ControllerException(ControllerException::ERROR_MSG_NO_CONTROLLER_PERMISSION);
                break;
        }
    }

    /**
     * Get user data
     * @return array|bool
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * @param $password
     * @return string
     */
    public function encryptPassword($password)
    {
        return md5(crypt($password, Config::getConfig("SALT_MD5")));
    }

    /**
     * Set jwt
     * @param array $data ["id" =>, "name" => , "permission" =>]
     * @return string
     */
    public function setJWT($data)
    {
        $jwt = JWT::encode($data, Config::getConfig("JWT_KEY_USER"));
        Globals::setSession("jwt", $jwt);

        return $jwt;
    }

    /**
     * User log out
     */
    public function userLogout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        $this->response->redirect("/back/login/");
    }

    /**
     * @return \Jennifer\Http\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \Jennifer\Http\Response $response
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     * @return Authentication
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }
}