<?php

namespace jennifer\auth;

interface AuthenticationInterface
{
    public function setJWT($data);

    public function getJWT();

    public function encryptPassword($password);

    public function checkUserPermission($requiredPermission = false, $checkType = "view");
}