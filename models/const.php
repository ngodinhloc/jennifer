<?php
  /*
   * Framework constants
   */
  define("DOC_ROOT", $_SERVER['DOCUMENT_ROOT']);
  // DIRECTORY
  const TEMPLATE_DIR = DOC_ROOT . "/templates/";
  const CLASS_DIR = "models/";
  const VIEW_DIR = "views/";
  const CONTROLLER_DIR = "cons/";
  const CACHE_DIR = "caches/";
  // EXTENSIONS
  const MODEL_EXT = ".php";
  const VIEW_EXT = ".class.php";
  const CONTROLLER_EXT = ".php";
  const SERVICE_EXT = ".php";
  const TEMPLATE_EXT = ".tpl.php";
  // MODULE, VIEW
  const MODULE_LIST = ["front", "back"];
  const DEFAULT_MODULE = "front";
  const DEFAULT_VIEW = "index";
  const LOGIN_VIEW_URL = "/back/index/";
  // DATABASE
  const DB_HOST = '*****';
  const DB_USER = '*****';
  const DB_PASSWORD = '*****';
  const DB_NAME = '*****';
  // SALT
  const SALT_MD5 = '*****';
  const SALT_SHA256 = '*****';
  const SALT_SHA512 = '*****';
  const JWT_KEY_USER = '*****';
  const JWT_KEY_API = '*****';

  /*
   * Thedaysoflife constants
   */
  //URL
  const SITE_URL = 'http://www.thedaysoflife.com';
  const PHOTO_URL = 'http://photos.thedaysoflife.com/';
  const BASE_URL = SITE_URL . '/index.php';
  const CONTROLLER_URL = SITE_URL . '/controllers/index.php';
  const LIST_URL = SITE_URL . '/day/';
  const URL_EXT = '.html';
  // META
  const SITE_TITLE = 'The Days Of Life';
  const SITE_AUTHOR = 'The Days Of Life';
  const SITE_DESCRIPTION = 'Share memories, inspire people';
  const SITE_KEYWORDS = 'the days of life, share to inspire, share memories, best day of life, best memories, inspiration';
  // NUMBER
  const PREVIEW_LENGTH = 500;
  const SUMMARY_LENGTH = 1000;
  const DESC_LENGTH = 200;
  const NUM_TOP_RIGHT = 10;
  const NUM_PER_PAGE_ADMIN = 40;
  const NUM_PER_PAGE = 12;
  const NUM_CALENDAR = 5;
  const NUM_PICTURE = 48;
  const NUM_PHOTO_UPLOAD = 10;
  //PHOTOS
  const PHOTO_DIR = "/uploads/photos/";
  const PHOTO_EXT = ".jpg";
  const PHOTO_FULL_NAME = "_full";
  const PHOTO_TITLE_NAME = "_title";
  const PHOTO_THUMB_NAME = "_thumb";
  const PHOTO_FULL_COMPRESS = 100;
  const PHOTO_TITLE_COMPRESS = 100;
  const PHOTO_THUMB_COMPRESS = 100;
  const PHOTO_FULL_WIDTH = 720;
  const PHOTO_FULL_HEIGHT = 720;
  const PHOTO_TITLE_WIDTH = 320;
  const PHOTO_TITLE_HEIGHT = 240;
  const PHOTO_THUMB_WIDTH = 75;
  const PHOTO_THUMB_HEIGHT = 75;
  const PHOTO_MAX_SIZE = 5; // MB