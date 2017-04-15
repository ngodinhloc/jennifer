<?php
namespace view;

use sys\System;

class Base {
  protected $module;
  protected $view;
  protected $headerTemplate;
  protected $footerTemplate;
  protected $contentTemplate;
  protected $title = SITE_TITLE;
  protected $description = SITE_DESCRIPTION;
  protected $keyword = SITE_KEYWORDS;
  protected $metaFiles = ["header" => [], "footer" => []];
  protected $metaHTML = ["header" => "", "footer" => ""];
  protected $data;
  protected $post = [];
  protected $para = [];
  protected $requiredPermission = false;

  public function __construct() {
    list($this->module, $this->view) = explode("\\", static::class);
    $this->checkPermission();
    $this->processPara();
  }

  /**
   * Check if a form or ajax is posted to the the view and store post para
   * @return bool
   */
  public function posted() {
    if (empty(System::getPOST())) {
      return false;
    }
    foreach (System::getPOST() as $key => $value) {
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
   * Process URI and GET para
   */
  protected function processPara() {
    $uri   = System::getRequestURI();
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

    $get = System::getGET();
    if (!empty($get)) {
      foreach ($get as $name => $value) {
        $this->para[$name] = $value;
      }
    }
  }

  /**
   * Check if user has permission to access view
   * @return bool
   */
  protected function checkPermission() {
    // no permission required
    if (!$this->requiredPermission) {
      return true;
    }
    System::sessionStart();
    $permissionList = System::getPermission();
    if (!$permissionList) {
      System::redirectTo("back/");
    }
    foreach ($this->requiredPermission as $per) {
      if (!in_array($per, $permissionList)) {
        System::redirectTo("back/");
      }
    }

    return true;
  }

  /**
   * Get the required permissions for this view
   */
  protected function getRequiredPermission() {
    return $this->requiredPermission;
  }

  /**
   * Load required permission from database or set required permission on each view
   */
  protected function loadRequiredPermission() {

  }

  /**
   * Add meta to header
   * @param string $file
   */
  public function addHeaderMeta($file) {
    $ext = System::getFileExtension($file);
    array_push($this->metaFiles["header"], ["type" => $ext, "src" => $file]);
  }

  /**
   * Add meta to footer
   * @param string $file
   */
  public function addFooterMeta($file) {
    $ext = System::getFileExtension($file);
    array_push($this->metaFiles["footer"], ["type" => $ext, "src" => $file]);
  }

  /**
   * Add html code to header
   * @param $html
   */
  public function addHeaderMetaHTML($html) {
    $this->metaHTML["header"] .= $html;
  }

  /**
   * Add html code to footer
   * @param $html
   */
  public function addFooterMetaHTML($html) {
    $this->metaHTML["footer"] .= $html;
  }

  /**
   * Render meta to html
   */
  protected function renderMeta() {
    foreach ($this->metaFiles as $pos => $files) {
      if (!empty($files)) {
        foreach ($files as $file) {
          switch($file["type"]) {
            case "css":
              $html = "<link rel='stylesheet' href='{$file["src"]}' type='text/css'/>";
              break;
            case "js":
              $html = "<script type='text/javascript' src='{$file["src"]}' ></script>";
              break;
          }
          $this->metaHTML[$pos] .= $html;
        }
      }
    }
  }

  /**
   * Remove white space between html tags
   * @param $html
   * @return string
   */
  protected function tidyHTML($html) {
    $html = preg_replace('/(?<=>)\s+(?=<)/', "", $html);

    return $html;
  }

  /**
   * Render this view
   * @param $tidy bool
   * @return string
   */
  public function render($tidy = true) {
    $this->renderMeta();
    ob_start("ob_gzhandler");
    include_once(TEMPLATE_DIR . $this->module . "/" . $this->headerTemplate . TEMPLATE_EXT);
    include_once(TEMPLATE_DIR . $this->module . "/" . $this->contentTemplate . TEMPLATE_EXT);
    include_once(TEMPLATE_DIR . $this->module . "/" . $this->footerTemplate . TEMPLATE_EXT);
    $html = ob_get_clean();
    if ($tidy) {
      $html = $this->tidyHTML($html);
    }

    return $html;
  }
}