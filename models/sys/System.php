<?php
/**
 * System utility static class, this is the only model that deals with system variables
 * such as: session, cookie, $_POST, $_GET, $_REQUEST, $_SERVER, define
 */
namespace sys;

use core\View;
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
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    return session_id();
  }

  public static function getGetPara($name = "", $default = null) {

  }

  public static function getRequest($name = "", $default = null) {

  }

  /**
   * @return mixed
   */
  public static function getFiles() {
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

  public static function getPostPara($name = "", $default = null) {

  }

  public static function getCookie($name = "", $default = null) {

  }

  public static function setCookie($name = "", $val = null) {

  }

  /**
   * @param $name
   * @param $val
   */
  public static function setSession($name, $val) {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    $_SESSION[$name] = $val;
  }

  /**
   * @param string $name
   * @param null $default
   * @return bool|null
   */
  public static function getSession($name = "", $default = null) {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
    else {
      if ($default) {
        return $default;
      }
      else {
        return false;
      }
    }
  }

  /**
   * @param $newpage
   */
  public static function redirectTo($newpage) {
    $host = $_SERVER['HTTP_HOST'];
    $uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location: http://$host$uri/$newpage");
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
   * Get search tag for modules/search
   * @return string
   */
  public static function getSearchPara() {
    $uri = $_SERVER['REQUEST_URI'];
    $tag = urldecode(trim(str_replace("/search/tag=", "", $uri)));

    return $tag;
  }

  /**
   * Get the parameter id for /day/para
   * @return int
   */
  public static function getDayPara() {
    $uri  = $_SERVER['REQUEST_URI'];
    $para = explode("/", $uri);

    return (int)$para[2];
  }

  /**
   * @return string
   */
  public static function loadView() {
    $uri = $_SERVER['REQUEST_URI'];
    list($domain, $module, $view) = explode("/", $uri);
    if (!$view) {
      $view = DEFAULT_VIEW;
    }
    if (!in_array($module, MODULE_LIST)) {
      $view   = $module;
      $module = DEFAULT_MODULE;
    }
    $file = VIEW_DIR . $module . "/" . $view . VIEW_EXT;
    if (file_exists($file)) {
      $class = $module . "\\" . $view;
    }
    else {
      $file  = VIEW_DIR . DEFAULT_MODULE . "/" . DEFAULT_VIEW . VIEW_EXT;
      $class = DEFAULT_MODULE . "\\" . DEFAULT_VIEW;
    }
    require_once($file);

    return $class;
  }

  /**
   * @param $conName
   * @return bool|string
   */
  public static function loadController($conName) {
    $baseCon = CONTROLLER_DIR . "Controller.php";
    $file    = CONTROLLER_DIR . $conName . CONTROLLER_EXT;
    if (file_exists($file)) {
      $class = str_replace("/", "", CONTROLLER_DIR) . "\\" . $conName;
      require_once($baseCon);
      require_once($file);

      return $class;
    }
    else {
      return false;
    }
  }

  /**
   * @param $filename
   * @return mixed
   */
  public static function getFileExtension($filename) {
    $path_info = pathinfo($filename);

    return $path_info['extension'];
  }

  /**
   * Admin log out
   */
  public static function userLogout() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    session_destroy();
    System::redirectTo("back/");
  }
}