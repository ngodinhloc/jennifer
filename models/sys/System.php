<?php
/**
 * System utility static class, this is the only model that deals with system variables
 * such as: session, cookie, $_POST, $_GET, $_REQUEST, $_SERVER, define
 */
namespace sys;

use jwt\JWT;

class System {
  /**
   * Get user permission from session
   * @return array
   */
  public static function getPermission() {
    $user = self::checkJWT();
    if ($user) {
      return explode(",", $user->permission);
    }

    return false;
  }

  /**
   * @param $password
   * @return string
   */
  public static function encryptPassword($password) {
    $password = crypt($password, SALT_MD5);
    $password = md5($password);

    return $password;
  }

  /**
   * Check jwt in session
   * @return array|bool
   */
  public static function checkJWT() {
    $jwt = self::getSession("jwt");
    if ($jwt) {
      $decoded = JWT::decode($jwt, JWT_KEY, ['HS256']);
      if (isset($decoded->id) && isset($decoded->name) && isset($decoded->permission)) {
        return $decoded;
      }
    }

    return false;
  }

  /**
   * Set jwt
   * @param int $id
   * @param string $name
   * @param string $permission
   * @return string
   */
  public static function setJWT($id, $name, $permission) {
    $token = [
      "id"         => $id,
      "name"       => $name,
      "permission" => $permission,
    ];
    $jwt   = JWT::encode($token, JWT_KEY);
    self::setSession("jwt", $jwt);

    return $jwt;
  }

  /**
   * Start ob compression
   */
  public static function obStart() {
    ob_start("ob_gzhandler");
  }

  /**
   * Output ob
   */
  public static function obFlush() {
    ob_flush();
    flush();
  }

  /**
   * Start session
   */
  public static function sessionStart() {
    session_start();
  }

  /**
   * @return string
   */
  public static function sessionID() {
    self::checkSession();

    return session_id();
  }

  /**
   * @param $name
   * @param $val
   */
  public static function setSession($name, $val) {
    self::checkSession();
    $_SESSION[$name] = $val;
  }

  /**
   * @param string $name
   * @param null $default
   * @return bool|null
   */
  public static function getSession($name = "", $default = null) {
    self::checkSession();
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
    if ($default) {
      return $default;
    }

    return false;
  }

  private static function checkSession() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  /**
   * @return mixed
   */
  public static function getRequestURI() {
    return $_SERVER['REQUEST_URI'];
  }

  /**
   * @return mixed
   */
  public static function getSERVER() {
    return $_SERVER;
  }

  /**
   * @return mixed
   */
  public static function getFILES() {
    return $_FILES;
  }

  /**
   * @return mixed
   */
  public static function getPOST() {
    return $_POST;
  }

  /**
   * @return mixed
   */
  public static function getGET() {
    return $_GET;
  }

  /**
   * Get post para
   * @param string $name
   * @param null $default
   * @return bool|string
   */
  public static function getPostPara($name = "", $default = null) {
    if (isset($_POST[$name])) {
      return $_POST[$name];
    }
    if ($default) {
      return $default;
    }

    return false;
  }

  /**
   * Get get para
   * @param string $name
   * @param null $default
   * @return bool|string
   */
  public static function getGetPara($name = "", $default = null) {
    if (isset($_GET[$name])) {
      return $_GET[$name];
    }
    if ($default) {
      return $default;
    }

    return false;
  }

  /**
   * Get get para
   * @param string $name
   * @param null $default
   * @return bool|string
   */
  public static function getFilePara($name = "", $default = null) {
    if (isset($_FILES[$name])) {
      return $_FILES[$name];
    }
    if ($default) {
      return $default;
    }

    return false;
  }

  public static function getCookie($name = "", $default = null) {

  }

  public static function setCookie($name = "", $val = null) {

  }

  /**
   * @param $newpage
   */
  public static function redirectTo($newpage) {
    $host = $_SERVER['HTTP_HOST'];
    $uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location: http://{$host}{$uri}{$newpage}");
    exit();
  }

  /**
   * @param $uri
   */
  public static function jsRedirect($uri) {
    echo("<script>window.location.href = '{$uri}'</script>");
  }

  /**
   * @return mixed
   */
  public static function getRealIPaddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {        //check ip from share internet
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {    //to check ip is pass from proxy
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
  }

  /**
   * @return mixed|string
   */
  public static function getTodayIPaddress() {
    $today = date('Ymd');
    $ip    = self::getRealIPaddress();
    $ip    = $today . '-' . $ip;

    return $ip;
  }

  /**
   * @return string
   */
  public static function loadView() {
    $uri = $_SERVER['REQUEST_URI'];
    list($domain, $module, $view) = explode("/", $uri);
    // there is no view => get default view
    if (!$view) {
      $view = DEFAULT_VIEW;
    }
    // module is not in module list => this is default module which does not require module name in uri
    if (!in_array($module, MODULE_LIST)) {
      $view   = $module;
      $module = DEFAULT_MODULE;
    }
    $file = VIEW_DIR . $module . "/" . $view . VIEW_EXT;
    if (file_exists($file)) {
      $class = $module . "\\" . $view;
    }
    else {
      // no class file exists => get default module and default view
      $file  = VIEW_DIR . DEFAULT_MODULE . "/" . DEFAULT_VIEW . VIEW_EXT;
      $class = DEFAULT_MODULE . "\\" . DEFAULT_VIEW;
    }
    require_once($file);

    return $class;
  }

  /**
   * @return bool|array
   */
  public static function loadController() {
    $action     = self::getPostPara("action");
    $controller = self::getPostPara("controller");
    $file       = CONTROLLER_DIR . $controller . CONTROLLER_EXT;
    if (file_exists($file)) {
      $class = str_replace("/", "", CONTROLLER_DIR) . "\\" . $controller;
      require_once($file);

      return [$class, $action];
    }

    return false;
  }

  /**
   * @param $filename
   * @return mixed
   */
  public static function getFileExtension($filename) {
    $pathInfo = pathinfo($filename);

    return $pathInfo['extension'];
  }

  /**
   * Admin log out
   */
  public static function userLogout() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    session_destroy();
    self::redirectTo("/back/");
  }
}