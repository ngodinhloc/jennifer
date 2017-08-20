<?php
/**
 * Base view class: all view classes will extend this base class
 */
namespace view;

use auth\Authentication;
use html\JObject;
use io\Output;
use sys\Globals;
use sys\System;

class Base implements ViewInterface {
  protected $module;
  protected $view;
  protected $headerTemplate;
  protected $footerTemplate;
  protected $contentTemplate;
  protected $title = SITE_TITLE;
  protected $description = SITE_DESCRIPTION;
  protected $keyword = SITE_KEYWORDS;
  protected $metaFiles = ["header" => [], "footer" => []];
  protected $metaTags = ["header" => "", "footer" => ""];
  protected $post = [];
  protected $para = [];
  protected $data;
  protected $userData = false;
  protected $authentication;
  protected $output;
  protected $requiredPermission = false;

  public function __construct() {
    list($this->module, $this->view) = explode("\\", static::class);
    $this->authentication = new Authentication();
    $this->userData       = $this->authentication->getUserData();
    $this->authentication->checkUserPermission($this->requiredPermission, "view");
    $this->processPara();
    $this->output = new Output();
  }

  /**
   * Set view data
   * @param $data
   */
  public function setData($data) {
    $this->data = $data;
  }

  /**
   * Get view data
   * @return mixed
   */
  public function getData() {
    return $this->data;
  }

  /**
   * Get user data
   * @return bool
   */
  public function getUserData() {
    return $this->userData;
  }

  /**
   * Get the required permissions for this view
   */
  public function getRequiredPermission() {
    return $this->requiredPermission;
  }

  /**
   * Load required permission from database or set required permission on each view
   */
  protected function loadRequiredPermission() {

  }

  /**
   * Check if a form or ajax is posted to the the view and store post para
   * @return bool
   */
  public function posted() {
    if (empty(Globals::post())) {
      return false;
    }
    foreach (Globals::post() as $key => $value) {
      $this->post[$key] = $value;
    }

    return true;
  }

  /**
   * Check if post para exists then return value, else return false
   * @param $name
   * @return bool|mixed
   */
  public function hasPost($name) {
    return isset($this->post[$name]) ? $this->post[$name] : false;
  }

  /**
   * Check if uri para exists then return value, else return false
   * @param $name
   * @return bool|mixed
   */
  public function hasPara($name) {
    return isset($this->para[$name]) ? $this->para[$name] : false;
  }

  /**
   * Add html code to header
   * @param $tag
   */
  public function addMetaTag($tag) {
    $this->metaTags["header"] .= $tag;
  }

  /**
   * Add meta file
   * @param string $file
   */
  public function addMetaFile($file) {
    $ext = System::getFileExtension($file);
    switch($ext) {
      case "css":
        array_push($this->metaFiles["header"], ["type" => $ext, "src" => $file]);
        break;
      case "js":
        array_push($this->metaFiles["footer"], ["type" => $ext, "src" => $file]);
        break;
    }
  }

  /**
   * Register object meta files
   * @param JObject $object
   */
  public function registerMetaFiles($object) {
    $metaFiles = $object->metaFiles;
    foreach ($metaFiles as $file) {
      $this->addMetaFile($file);
    }
  }

  /**
   * Render this view
   * @param $compress bool
   */
  public function render($compress = true) {
    $this->initMetaTags();
    ob_start("ob_gzhandler");
    include_once(TEMPLATE_DIR . $this->module . "/" . $this->headerTemplate . TEMPLATE_EXT);
    include_once(TEMPLATE_DIR . $this->module . "/" . $this->contentTemplate . TEMPLATE_EXT);
    include_once(TEMPLATE_DIR . $this->module . "/" . $this->footerTemplate . TEMPLATE_EXT);
    $html = ob_get_clean();
    $this->output->html($html, $compress);
  }

  /**
   * Process URI and GET para
   */
  protected function processPara() {
    $uri   = Globals::server("REQUEST_URI");
    $paras = explode("/", $uri);
    $index = ($this->module == DEFAULT_MODULE) ? 1 : 2;
    if (isset($paras[$index])) {
      switch($paras[$index]) {
        case "day":
          $this->para["day"] = $paras[$index + 1];
          break;
        case "search":
          $this->para["search"] = urldecode(trim(str_replace("/search/", "", $uri)));
          break;
      }
    }

    $get = Globals::get();
    if (!empty($get)) {
      foreach ($get as $name => $value) {
        $this->para[$name] = $value;
      }
    }
  }

  /**
   * Initialise meta tags to html
   */
  protected function initMetaTags() {
    foreach ($this->metaFiles as $pos => $files) {
      if (!empty($files)) {
        array_unique($files);
        $tags = "";
        foreach ($files as $file) {
          switch($file["type"]) {
            case "css":
              $tag = "<link rel='stylesheet' href='{$file["src"]}' type='text/css'/>";
              break;
            case "js":
              $tag = "<script type='text/javascript' src='{$file["src"]}' ></script>";
              break;
          }
          $tags .= $tag;
        }
        $this->metaTags[$pos] = $tags . $this->metaTags[$pos];
      }
    }
  }
}