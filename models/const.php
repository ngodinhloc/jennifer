<?php
// DATABASE
define('DB_HOST', '***');
define('DB_USER', '***');
define('DB_PASSWORD', '***');
define('DB_NAME', '***');
// DITECTORY
define("CLASS_DIR", "models/");
define("VIEW_DIR", "views/");
define("TEMPLATE_DIR", "templates/");
define("DASHBOARD_DIR", "modules/");
define("CONTROLLER_DIR", "cons/");
define("CACHE_DIR", "caches/");
define("DEFAULT_VIEW", "index");
define("DEFAULT_MODULE", "front");
define("VIEW_EXT", ".class.php");
define("CONTROLLER_EXT", ".php");
define("TEMPLATE_EXT", ".tpl.php");
define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('SITE_URL', 'http://www.thedaysoflife.com');
define('BASE_URL', SITE_URL . '/index.php');
define('CONTROLLER_URL', SITE_URL . '/controllers/index.php');
define('URL_EXT', '.html');
define('LIST_URL', SITE_URL . '/front/day/');
define('PHOTO_DIR', '/uploads/photos/');
define('PHOTO_URL', 'http://photos.thedaysoflife.com/');
define('SITE_TITLE', 'The Days Of Life');
define('SITE_AUTHOR', 'The Days Of Life');
define('SITE_DESCRIPTION', 'Share memories, inspire people');
define('SITE_KEYWORDS', 'the days of life, share to inspire, share memories, best day of life, best memories, inspiration');
// NUMBER
define("CACHE_EXPIRE", 36000);
define('PREVIEW_LENGTH', 500);
define('SUMMARY_LENGTH', 1000);
define('DESC_LENGTH', 200);
define('NUM_TOP_RIGHT', 10);
define('NUM_PER_PAGE_ADMIN', 40);
define('NUM_PER_PAGE', 12);
define('NUM_CALENDAR', 5);
define('NUM_PICTURE', 48);
define('NUM_PHOTO_UPLOAD', 10);
define('CHECK_DB', 'check_all_tables');
define('ANALYZE_DB', 'analyze_all_tables');
define('REPAIR_DB', 'repair_all_tables');
define('OPTIMIZE_DB', 'optimize_all_tables');
define('ORDER_BY_ID', 'order_by_id');
define('ORDER_BY_LIKE', 'order_by_like');
//PHOTOS
define('PHOTO_EXT', ".jpg");
define('PHOTO_FULL_NAME', "_full");
define('PHOTO_TITLE_NAME', "_title");
define('PHOTO_THUMB_NAME', "_thumb");
define('PHOTO_FULL_COMPRESS', 100);
define('PHOTO_TITLE_COMPRESS', 100);
define('PHOTO_THUMB_COMPRESS', 100);
define('PHOTO_FULL_WIDTH', 720);
define('PHOTO_FULL_HEIGHT', 720);
define('PHOTO_TITLE_WIDTH', 320);
define('PHOTO_TITLE_HEIGHT', 240);
define('PHOTO_THUMB_WIDTH', 75);
define('PHOTO_THUMB_HEIGHT', 75);
// FB
define('FB_APPID', '***');
define('FB_SECRET', '***');
define('FB_PAGEID', '***');
define('FB_ALBUMID', '***');
define('FB_TEXT', 'text');
define('FB_FEED', 'feed');
define('FB_ALBUM', 'album');
define('FB_LINK', 'link');
// SALT
define('ADMIN_ACTIVE', 'active');
define('ADMIN_DISABLE', 'disable');
define('SALT_MD5', '***');
define('SALT_SHA256', '***');
define('SALT_SHA512', '***');
define("JWT_KEY", '***');
// LOGIN NOTICE
define('NOTICE_SESSION_EXPIRED', 'Session time out. Please <a title="Login" href="#login" onclick="self.parent.reloadPage();return false">login again</a>');