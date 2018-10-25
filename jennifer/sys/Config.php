<?php

namespace jennifer\sys;

define("DOC_ROOT", $_SERVER['DOCUMENT_ROOT']);

/**
 * Class Config
 * @package jennifer\sys
 */
class Config {
  // DIRECTORY
  const TEMPLATE_DIR   = DOC_ROOT . "/templates/";
  const CLASS_DIR      = "models/";
  const VIEW_DIR       = "views/";
  const CONTROLLER_DIR = "cons/";
  const CACHE_DIR      = "caches/";
  // EXTENSIONS
  const MODEL_EXT      = ".php";
  const VIEW_EXT       = ".php";
  const CONTROLLER_EXT = ".php";
  const SERVICE_EXT    = ".php";
  const TEMPLATE_EXT   = ".tpl.php";
  // MODULE, VIEW
  const MODULE_LIST    = ["front", "back"];
  const DEFAULT_MODULE = "front";
  const DEFAULT_VIEW   = "index";
  const LOGIN_VIEW_URL = "/back/index/";
  // DATABASE
  const DB_HOST     = '*****';
  const DB_USER     = '*****';
  const DB_PASSWORD = '*****';
  const DB_NAME     = '*****';
  // SALT
  const SALT_MD5     = '*****';
  const SALT_SHA256  = '*****';
  const SALT_SHA512  = '*****';
  const JWT_KEY_USER = '*****';
  const JWT_KEY_API  = '*****';
  // FACEBOOK : for use of FacebookHelper
  const FB_APPID   = '*****';
  const FB_SECRET  = '*****';
  const FB_PAGEID  = '*****';
  const FB_ALBUMID = '*****';
  // URL
  const SITE_URL       = 'http://www.thedaysoflife.com';
  const PHOTO_URL      = 'http://photos.thedaysoflife.com/';
  const BASE_URL       = self::SITE_URL . '/index.php';
  const CONTROLLER_URL = self::SITE_URL . '/controllers/index.php';
  const URL_EXT        = '.html';
  // UPLOAD FILE
  const PHOTO_MAX_SIZE = 5; // MB
  // ROUTING
  const ROUTING = ["modules" => [["name"    => "Front End",
                                  "path"    => "front",
                                  "default" => true,
                                  "views"   => ["index", "search"]],
                                 ["name"  => "Back End",
                                  "path"  => "back",
                                  "views" => ["login", "main"]],
  ],
  ];

  /**
   * TODO : implement routing
   * @return array
   */
  public function getRouting() {
    return self::ROUTING;
  }
}