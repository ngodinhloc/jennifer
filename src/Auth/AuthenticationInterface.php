<?php

namespace Jennifer\Auth;

interface AuthenticationInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function setJWT($data);

    /**
     * @return mixed
     */
    public function getJWT();

    /**
     * @param $password
     * @return mixed
     */
    public function encryptPassword($password);

    /**
     * @param bool $requiredPermission
     * @param string $checkType
     * @return mixed
     */
    public function checkUserPermission($requiredPermission = false, $checkType = "view");
}