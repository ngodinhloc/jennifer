# Jennifer - The PHP Framework #

Jennifer is a PHP framework that I first created when I was doing my master at University of Sydney in 2009. There has been a few changes since the first version. The framework was recently named after my daughter.

The source code using in this example is the actual code of Thedaysoflife project https://github.com/ngodinhloc/thedaysoflife.com

### Contents
- [Ajax MVC Pattern](#ajax-mvc-pattern)
- [The Framework Structure](#the-framework-tructure)
    - Models
    - Views
    - Controllers
    - Templates
- [Single Point Entry](#single-point-entry)
    - index.php
    - .htaccess
- [Models](#models)
    - db\DB.php
    - html\HTML.php
    - core\View.php
    - sys\System.php
- [Views](#views)
    - about.php
- [Controllers](#controllers)
    - index.php
    - ControllerView.php
- [Templates](#templates)
    - fron\index.tpl.php
- [Ajax](#ajax)
    - ajax.thedaysoflife.js

### The Framework Structure
### Ajax MVC Pattern
In Ajax MVC Pattern (aMVC): actions are sent from views to controllers via ajax
<pre>views -> ajax -> controllers -> models</pre>

### Single Point Entry
#### index.php
<pre>
<?php
<?php
/**
 * Single point entry
 * <pre>mod_rewrite in to redirect all request to this index page (except for the listed directories)
 * process request uri to get view and load view
 * </pre>
 */
require_once("models/autoload.php");
use sys\System;

$viewClass = System::loadView();
if ($viewClass) {
  $view = new $viewClass() or die("View not found: " . $viewClass);
  $html = $view->render();
  echo($html);
}
</pre>

#### .htaccess
<pre>
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !(/controllers|/interface|/js|/plugins|/views)
RewriteRule ^(.*)$ index.php?q=$1 [L,QSA]
</pre>

### Models
#### db\DB.php
<pre>
/**
 * Database class : this is the only model that has access to database by using mysqli
 */
namespace db;
class DB {
private $mysqli;
private $tableName;
private $sql;
private $selectCols;
private $insertCols;
private $insertVals;
private $updateVals;
private $whereCond;
private $orderBy;
private $groupBy;
private $innerjoin;
private $leftJoin;
private $rightJoin;
private $offset;
private $limit;
private $result;
private $foundRows;
private $devMode = false;

  /**
   * Get results:
   * $db->table('tbl_day')->select([col1,col2])->where([col1 => val1, col2 => val2])
   *                 ->groupBy([col1,col2])->orderBy([col1=>ASC,col2=>DESC])->offset(0)->limit(20)
   *                 ->get()->toArray();
   * @param bool $foundRows : get found row or not
   * @param bool|string $cache :'mem' => Memchached, 'file' => FileCache;
   * @return $this|array
   */
  public function get($foundRows = false, $cache = false) {}
</pre>

#### html\HTML.php
<pre>
/**
 * This class is used to create HTML element (so that we no longer print HTML code inside models)
 */
namespace html;

class HTML {
  private $tag;
  private $id;
  private $name;
  private $class;
  private $propList = [];
  private $innerHTML;
  
  /**
   * Open HTML tag
   * @return string
   */
  public function open() {}

  /**
   * Close HTML element
   * @return string
   */
  public function close() {}

  /**
   * Create the element
   * @return string
   */
  public function create() {}
}
</pre>

#### core\View.php
<pre>
namespace core;
use html\HTML;
use sys\System;
use com\Com;

class View extends Model {

  /**
   * Get info by tag
   * @param string $tag
   * @return array
   */
  public function getInfoByTag($tag) {
    $row = $this->db->table("tbl_info")->select(["title", "content"])->where(["tag" => $tag])
                    ->get(false, "file")->first();

    return $row;
  }
  
  /**
   * Get the top number of days for the right column
   * @return string
   */
  public function getRightTopDay() {
    $result = $this->db->table("tbl_day")->select(["id", "day", "year", "month", "slug", "title", "photos"])
                       ->orderBy(["like" => "DESC"])->limit(NUM_TOP_RIGHT)->get(false, "file")->toArray();

    $html   = new HTML();
    $output = "";
    if ($result) {
      foreach ($result as $row) {
        if (isset($row['id'])) {
          $link       = Com::getDayLink($row);
          $photos     = trim($row['photos']);
          $firstPhoto = "";
          if ($photos != "") {
            $photos     = explode(',', $photos);
            $photo      = $photos[0];
            $photoUrl   = Com::getPhotoURL($photo, PHOTO_THUMB_NAME);
            $firstPhoto = $html->setTag("img")->setProp(["src" => $photoUrl])->create();
          }
          $output .= $html->setTag("li")->setClass("right-list")->open() .
                     $html->setTag("div")->setClass("right-thumb")->open() .
                     $html->setTag("a")->setProp(["href" => $link])->setInnerHTML($firstPhoto)->create() .
                     $html->setTag("div")->close() .
                     $html->setTag("div")->setClass("right-title")->open() .
                     $html->setTag("a")->setProp(["href" => $link])->setInnerHTML(stripslashes($row['title']))
                          ->create() .
                     $html->setTag("div")->close() .
                     $html->setTag("div")->setClass("clear-both")->create() .
                     $html->setTag("li")->close();
        }
      }
    }

    return $output;
  }
}
</pre>
#### sys\System.php
<pre>
/**
 * System utility static class, this is the only model that deals with system variables
 * such as: session, cookie, $_POST, $_GET, $_REQUEST, $_SERVER, define
 */
namespace sys;

use jwt\JWT;

class System {
/**
   * Get the view from uri (if not view found then get default) , define SYS_VIEW
   * @return string
   */
  public static function setView() {
    $uri      = $_SERVER['REQUEST_URI'];
    $para     = explode("/", $uri);
    $viewName = $para[1];

    if (file_exists(VIEW_DIR . $viewName . VIEW_EXT)) {
      define('SYS_VIEW', $viewName);
    }
    else {
      define('SYS_VIEW', 'index');
    }
  }

  /**
   * Load the view: always call after getView()
   * @see setView();
   */
  public static function loadView() {
    $viewFile = VIEW_DIR . SYS_VIEW . VIEW_EXT;
    include_once($viewFile);
  }
}
</pre>
### Controllers
#### index.php
<pre>
/**
 * Single entry point for controllers: all ajax actions point to this page with a pair of {action, controller}
 */
require_once("../models/autoload.php");

use sys\System;

$para       = System::getPOST();
$action     = $para["action"];
$controller = $para["controller"];
$conClass   = System::loadController($controller);
if ($conClass) {
  $con = new $conClass() or die("Class not found: " . $conClass);
  $con->$action($para);
}
</pre>
#### ControllerView.php
<pre>
namespace cons;
  use core\View;
  class ControllerView extends Controller {
    private $view;

    public function __construct() {
      $this->view = new View();
    }

    public function ajaxShowDay($para) {
      $from = (int)$para['from'];
      $order = $para['order'];
      if ($from > 0) {
        echo($this->view->getBestDays($from, $order));
      }
      exit();
    }
</pre>

### Ajax
#### ajax.thedaysoflife.js
<pre>
/**
 * Do ajax action
 * @param actionPara array ["action", "controller]
 * @param method "POST", "GET"
 * @param para object {"para":"value",...}
 * @param loader string #loader
 * @param containerPara array ["container", "append|replace"]
 * @param callback callback function
 */
function ajaxAction(actionPara, method, para, loader, containerPara, callback) {
}
/**
 * show more days
 * @param from
 * @param order
 */
function ajaxShowDay(from, order) {
  callback = processDays;
  ajaxAction({"action": "ajaxShowDay", "controller": "ControllerView"}, $.param({"from": from, "order": order}),
    "#show-more", false, callback);
}
...
</pre>
