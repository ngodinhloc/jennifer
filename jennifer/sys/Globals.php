<?php

namespace jennifer\sys;
/**
 * Class Globals: utility static class, this is the only model that deals with system variables
 * such as: session, cookie, $_POST, $_GET, $_REQUEST, $_SERVER
 * @package jennifer\sys
 */
class Globals
{
    /**
     * Start session
     */
    public static function sessionStart()
    {
        session_start();
    }

    /**
     * Get session id
     * @return string
     */
    public static function sessionID()
    {
        self::checkSession();

        return session_id();
    }

    /**
     * Check is session status
     */
    private static function checkSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @param $name
     * @param $val
     */
    public static function setSession($name = null, $val = null)
    {
        if ($name) {
            self::checkSession();
            $_SESSION[$name] = $val;
        }
    }

    /**
     * @param string $name
     * @param null $default
     * @return bool|null
     */
    public static function session($name = null, $default = null)
    {
        self::checkSession();
        if (!$name) {
            return $_SESSION;
        }
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        if ($default) {
            return $default;
        }

        return false;
    }

    /**
     * @param string $name
     * @param null $default
     * @return bool|null
     */
    public static function cookie($name = null, $default = null)
    {
        if (!$name) {
            return $_COOKIE;
        }
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
        if ($default) {
            return $default;
        }

        return false;
    }

    /**
     * @param string $name
     * @param null $val
     */
    public static function setCookie($name = null, $val = null)
    {
        if ($name) {
            $_COOKIE[$name] = $val;
        }
    }

    /**
     * Get _POST para
     * @param string $name
     * @param null $default
     * @return bool|string|array
     */
    public static function post($name = null, $default = null)
    {
        // not para name provide: return all _POST
        if (!$name) {
            return $_POST;
        }
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
        if ($default) {
            return $default;
        }

        return false;
    }

    /**
     * Get _GET para
     * @param string $name
     * @param null $default
     * @return bool|string|array
     */
    public static function get($name = null, $default = null)
    {
        // not para name provide: return all _GET
        if (!$name) {
            return $_GET;
        }
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
        if ($default) {
            return $default;
        }

        return false;
    }

    /**
     * Get _FILES para
     * @param string $name
     * @param null $default
     * @return bool|string|array
     */
    public static function files($name = null, $default = null)
    {
        if (!$name) {
            return $_FILES;
        }
        if (isset($_FILES[$name])) {
            return $_FILES[$name];
        }
        if ($default) {
            return $default;
        }

        return false;
    }

    /**
     * Get _SERVER para
     * @param string $name
     * @param null $default
     * @return bool|string|array
     */
    public static function server($name = null, $default = null)
    {
        if (!$name) {
            return $_SERVER;
        }
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
        if ($default) {
            return $default;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public static function docRoot()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    /**
     * @return mixed|string
     */
    public static function todayIPAddress()
    {
        $today = date('Ymd');
        $ip = self::realIPAddress();
        $ip = $today . '-' . $ip;

        return $ip;
    }

    /**
     * @return mixed
     */
    public static function realIPAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {        //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {    //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}