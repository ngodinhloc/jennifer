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
  protected $requiredPermission = false;

  public function __construct() {
    $this->checkPermission();
    list($this->module, $this->view) = explode("\\", static::class);
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
      die("User does not have permission to access this view");
    }
    foreach ($this->requiredPermission as $per) {
      if (!in_array($per, $permissionList)) {
        die("User does not have permission to access this view");
      }
    }

    return true;
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
   * Remove white space between tags
   * @param $html
   * @return array
   */
  protected function tidyHTML($html) {
    $html   = str_replace(["\r\n", "\r"], "\n", $html);
    $lines  = explode("\n", $html);
    $output = [];

    foreach ($lines as $line) {
      if (!empty($line)) {
        $output[] = trim($line);
      }
    }

    return implode($output);
  }

  /**
   * Render this view
   * @return string
   */
  public function render() {
    $this->renderMeta();
    ob_start("ob_gzhandler");
    include_once(TEMPLATE_DIR . $this->module . "/" . $this->headerTemplate . TEMPLATE_EXT);
    include_once(TEMPLATE_DIR . $this->module . "/" . $this->contentTemplate . TEMPLATE_EXT);
    include_once(TEMPLATE_DIR . $this->module . "/" . $this->footerTemplate . TEMPLATE_EXT);
    $html = ob_get_clean();

    return $this->tidyHTML($html);
  }
}