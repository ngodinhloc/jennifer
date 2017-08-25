<?php
namespace sys;
/**
 * Class System: System utility static class: load views, controllers, do redirect
 * @package sys
 */
class System {
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
   * @param $newpage
   */
  public static function redirectTo($newpage) {
    $host = Globals::server("HTTP_HOST");
    $uri  = rtrim(dirname(Globals::server("PHP_SELF")), '/\\');
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
   * @return string
   */
  public static function loadView() {
    $uri = Globals::server("REQUEST_URI");
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
    $action     = Globals::post("action");
    $controller = Globals::post("controller");
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
}