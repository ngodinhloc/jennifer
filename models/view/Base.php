<?php
namespace view;

use auth\Authentication;
use cache\CacheInterface;
use cache\FileCache;
use html\JObject;
use io\Output;
use sys\Globals;
use sys\System;
use template\Template;

/**
 * Class Base: Base view class: all view classes will extend this base class
 * @package view
 */
class Base {
  /** @var Authentication */
  protected $authentication;
  /** @var  Template */
  protected $tpl;
  /** @var Output */
  protected $output;
  /** @var CacheInterface */
  protected $cacher;
  /** @var  bool cache this view or not */
  protected $cache;
  /** @var array list of templates used in the view */
  protected $templates = [];
  /** @var array view data */
  protected $data = [];
  /** @var array meta data : module, view, title, description, keyword, metaTags, userData
   * Only $data and $meta are accessible in templates */
  protected $meta = [];
  /** @var array store _POST */
  protected $post = [];
  /** @var array store _GET and uri  para */
  protected $para = [];
  /** @var array|bool user data */
  protected $userData = false;
  /** @var bool|array required permission of the view */
  protected $requiredPermission = false;
  /** @var  string module */
  protected $module;
  /** @var  string view class name */
  protected $view;
  /** @var  string uri of the view */
  protected $uri;
  /** @var  string title of the view */
  protected $title;
  /** @var  string description of the view */
  protected $description;
  /** @var  string keywords of the view */
  protected $keyword;

  protected $headerTemplate;
  protected $footerTemplate;
  protected $contentTemplate;
  protected $metaFiles = ["header" => [], "footer" => []];
  protected $metaTags = ["header" => "", "footer" => ""];

  public function __construct() {
    list($this->module, $this->view) = explode("\\", static::class);
    $this->uri            = Globals::server("REQUEST_URI");
    $this->authentication = new Authentication();
    $this->authentication->checkUserPermission($this->requiredPermission, "view");
    $this->userData = $this->authentication->getUserData();
    $this->output   = new Output();
    $this->cacher   = new FileCache();
    $this->processPara();
    if ($this->cache) {
      $this->retrieveCache();
    }
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
   * Process URI and GET para
   */
  protected function processPara() {
    $paras = explode("/", $this->uri);
    $index = ($this->module == DEFAULT_MODULE) ? 1 : 2;
    if (isset($paras[$index])) {
      switch($paras[$index]) {
        case "day":
          $this->para["day"] = $paras[$index + 1];
          break;
        case "search":
          $this->para["search"] = urldecode(trim(str_replace("/search/", "", $this->uri)));
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
   * Initialise view meta data
   */
  protected function initMeta() {
    $this->initMetaTags();
    $this->meta = ["module"      => $this->module,
                   "view"        => $this->view,
                   "title"       => $this->title,
                   "description" => $this->description,
                   "keyword"     => $this->keyword,
                   "metaTags"    => $this->metaTags,
                   "userData"    => (array)$this->userData,
    ];
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
          $tag = "";
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

  /**
   * Check if there is valid cache
   * If there is cache than output the cache and exit
   * else : process to prepare data and render
   */
  protected function retrieveCache() {
    $cache = $this->cacher->getCache($this->uri);
    if ($cache) {
      $this->output->html($cache);
    }
  }

  /**
   * Render this view
   * @param bool $compress
   */
  public function render($compress = true) {
    $this->initMeta();
    $this->templates = [$this->module . "/" . $this->headerTemplate,
                        $this->module . "/" . $this->contentTemplate,
                        $this->module . "/" . $this->footerTemplate,
    ];
    $this->tpl       = new Template($this->templates, $this->data, $this->meta);
    $html            = $this->tpl->render($compress);
    // cache the whole view html
    if ($this->cache) {
      $this->cacher->writeCache($this->uri, $html);
    }

    $this->output->html($html);
  }
}