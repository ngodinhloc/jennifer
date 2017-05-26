# Jennifer - The PHP Framework #

Jennifer is a simple PHP framework that implements the Ajax MVC pattern. I created Jennifer framework when I was doing my master at University of Sydney in 2009, there has been a few changes since the first version.

The source code using in this example is the actual code of Thedaysoflife project https://github.com/ngodinhloc/thedaysoflife.com

### Jennifer Framework
- [Ajax MVC Pattern](#ajax-mvc-pattern)
- [The Framework Structure](#the-framework-tructure)
    - models
    - views 
    - controllers 
    - templates 
    - js 
    - plugins 
    - caches
- [Single Point Entry](#single-point-entry)
    - index.php
    - .htaccess
- [Models](#models)
    - db\DB.php
    - view\Base.php
    - tpl\Template.php
    - cons\Controller.php
    - sys\System.php
    - html\HTML.php
    - cache\FileCache.php
    - thedaysoflife\User.php
    - thedaysoflife\Admin.php
- [Views](#views)
    - about.php
- [Controllers](#controllers)
    - index.php
    - ControllerView.php
- [Templates](#templates)
    - fron\index.tpl.php
- [Ajax](#ajax)
    - ajax.thedaysoflife.js

### Ajax MVC Pattern
In Ajax MVC Pattern (aMVC): actions are sent from views to controllers via ajax
<pre>views -> ajax -> controllers -> models</pre>

### The Framework Structure
- models: contains all the packages and models which are the heart of Jennifer framework
- views: contains all view classes. View classes are placed under each module. In the sample code, we have 2 modules: "back" and "front", each module has serveral views.
- controllers: contains all controller classes. Each module may have one or more controllers
- templates: contains all templates using in views, models and controllers. Templates are organised under module just like view. There are view templates and content templates. Each view has one view template with similar file name. For example: the index view (index.class.php) is using index template (index.tpl.php). Content templates are placed inside "tpl" folder, content templates may be used to render html content in views, models or controllers.
- js: contains ajax.js and other js files
- plugins: contains all plugins, such as: bootstrap, ckeditor, jquery
- caches: contains cache files for mysql queries
### Single Point Entry
#### index.php
<pre>
/**
 * Single point entry
 * mod_rewrite in to redirect all request to this index page (except for the listed directories)
 * process request uri to get view and load view
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
RewriteCond %{REQUEST_URI} !(/controllers/|/interface/|/js/|/plugins/|/views/|/api/)
RewriteRule ^(.*)$ index.php?q=$1 [L,QSA]
</pre>

### Models
#### db\DB.php
<pre>
/**
 * Database class : this is the only model that has access to database by using mysqli
 */
namespace db;
use cache\FileCache;
use mysqli;
class DB implements DBInterface {
  /**
   * Get results:
   * $db->table('tbl_day')->select([col1,col2])->where([col1 => val1, col2 => val2])
   *                 ->groupBy([col1,col2])->orderBy([col1=>ASC,col2=>DESC])->offset(0)->limit(20)
   *                 ->get()->toArray();
   * @param bool $foundRows : get found row or not
   * @param bool|string $cache :'mem' => Memchached, 'file' => FileCache;
   * @return $this|boolean
   */
  public function get($foundRows = false, $cache = false) {
    if (!$this->checkTable()) {
      return false;
    }
    $table   = $this->tableName;
    $select  = ($foundRows) ? "SELECT SQL_CALC_FOUND_ROWS" : "SELECT";
    $columns = ($this->selectCols) ? $this->selectCols : "*";
    $where   = ($this->whereCond) ? $this->whereCond : "";
    $groupBy = ($this->groupBy) ? $this->groupBy : "";
    $orderBy = ($this->orderBy) ? $this->orderBy : "";
    $limit   = "";
    if (is_numeric($this->limit)) {
      $offset = is_numeric($this->offset) ? $this->offset : 0;
      $limit  = " LIMIT {$offset}, {$this->limit}";
    }
    $sql = "{$select} {$columns} FROM {$table}{$where}{$groupBy}{$orderBy}{$limit}";

    switch($cache) {
      case "file":
        $data = FileCache::getCache($sql);
        if ($data) {
          $this->result = $data["data"];
          if ($foundRows) {
            $this->foundRows = $data["found"];
          }

          return $this;
        }
        else {
          $result = $this->query($sql);
          if ($foundRows) {
            $this->setFoundRows();
          }
          $this->result = $this->resultToArray($result);

          $data = ["found" => $this->foundRows, "data" => $this->result];
          FileCache::writeCache($sql, $data);

          return $this;
        }
        break;
      case "mem":
        break;
      default:
        $result = $this->query($sql);
        if ($foundRows) {
          $this->setFoundRows();
        }
        $this->result = $this->resultToArray($result);

        return $this;
        break;
    }
  }
}
</pre>
#### view\Base.php
<pre>
/**
* Base view class: all view classes will extend this base class
*/
namespace view;
use com\Common;
use html\JObject;
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
    protected $requiredPermission = false;
    protected $messages = [
      "NO_PERMISSION"          => ["message" => "You do not have permission to access this view."],
      "NO_AUTHENTICATION"      => ["message" => "The view you tried to access requires authentication."],
      "INVALID_AUTHENTICATION" => ["message" => "Incorrect username or password."],
      "DISABLE_USER"           => ["message" => "This user is disabled."],
    ];
    
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
</pre>
#### tpl\Template.php
<pre>
namespace tpl;
use tpl\TemplateInterface;
class Template implements TemplateInterface {
  protected $template;
  protected $data;
  /**
   * Render templates
   * @param $tidy bool
   * @return string
   */
  public function render($tidy = true) {
    ob_start("ob_gzhandler");
    include_once(TEMPLATE_DIR . $this->template . TEMPLATE_EXT);
    $html = ob_get_clean();
    if ($tidy) {
      $html = $this->tidyHTML($html);
    }

    return $html;
  }
}
</pre>
#### cons\Controller.php
<pre>
/**
 * The base controller class: all controller will extend this class
 * Each public function of controller class is an action
 */
namespace cons;
use sys\System;
class Controller implements ControllerInterface {
    protected $requiredPermission = false;
  protected $userData = false;
  protected $post = [];
  protected $messages = [
    "NO_PERMISSION" => ["message" => "You do not have permission to access this controller."],
  ];

  public function __construct() {
    $this->checkPermission();
    $this->post = System::getPOST();
  }

  /**
   * Controller response
   * @param array|string $data
   * @param bool $json
   * @param int $jsonOpt
   */
  public function response($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES) {
    if (is_array($data)) {
      if ($json) {
        header('Content-Type: application/json');
        echo(json_encode($data, $jsonOpt));
        exit;
      }
      echo(json_encode($data, $jsonOpt));
      exit;
    }
    echo $data;
    exit;
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
class System {
     /**
       * @return string
       */
      public static function loadView() {
        $uri = $_SERVER['REQUEST_URI'];
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
        $action     = self::getPostPara("action");
        $controller = self::getPostPara("controller");
        $file       = CONTROLLER_DIR . $controller . CONTROLLER_EXT;
        if (file_exists($file)) {
          $class = str_replace("/", "", CONTROLLER_DIR) . "\\" . $controller;
          require_once($file);

          return [$class, $action];
        }

        return false;
      }
  }
}
</pre>
#### html\HTML.php
<pre>
/**
 * This class is used to create HTML element (so that we no longer print HTML code inside models)
 */
namespace html;
class HTML {
  /**
   * Open HTML tag
   * @return string
   */
  public function open() {
    $html = "<{$this->tag}{$this->initID()}{$this->initName()}{$this->initClass()}{$this->initProp()}>";

    return $html;
  }

  /**
   * Close HTML element
   * @return string
   */
  public function close() {
    $html = "</{$this->tag}>";

    return $html;
  }

  /**
   * Create the element
   * @return string
   */
  public function create() {
    $innerHTML = isset($this->innerHTML) ? $this->innerHTML : "";
    $html      = $this->open() . $innerHTML . $this->close();

    return $html;
  }
}
</pre>

#### thedaysoflife\User.php
<pre>
namespace thedaysoflife;
use html\HTML;
use sys\System;
use com\Com;
use core\Model;

class User extends Model {

 /**
   * Insert new day
   * @param array $day
   * @return bool|\mysqli_result
   */
  public function addDay($day) {
    $code   = mt_rand(100000, 999999);
    $result = $this->db->table("tbl_day")->columns(["day", "month", "year", "title", "slug", "content", "preview",
                                                    "sanitize", "username", "email", "location", "edit_code", "notify",
                                                    "photos", "like", "date", "time", "ipaddress", "session_id"])
                       ->values([$day["day"], $day["month"], $day["year"], $day["title"], $day["slug"], $day["content"],
                                 $day["preview"], $day["sanitize"], $day["username"], $day["email"],
                                 $day["location"], $code, $day["notify"], $day["photos"], $day["like"], $day["date"],
                                 $day["time"], $day["ipaddress"], $day["session_id"]])
                       ->insert();

    return $result;
  }
  
  /**
   * Get one day by id
   * @param int $id
   * @return array
   */
  public function getDayById($id) {
    $row = $this->db->table("tbl_day")->where(["id" => $id])->get()->first();

    return $row;
  }
  
}
</pre>
#### thedaysoflife/Admin.php
<pre>
namespace thedaysoflife;
use com\Common;
use tpl\Template;
use core\Model;
class Admin extends Model {
    /**
   * @param $page
   * @return string
   */
  public function getDayList($page) {
    $limit   = NUM_PER_PAGE_ADMIN;
    $from    = $limit * ($page - 1);
    $result  = $this->db->table("tbl_day")->select(["id", "title", "day", "month", "year", "slug", "username", "count",
                                                    "like", "fb"])
                        ->orderBy(["id" => "DESC"])->offset($from)
                        ->limit($limit)->get(true)->toArray();
    $total   = $this->db->foundRows();
    $pageNum = ceil($total / $limit);
    $tpl     = new Template("back/tpl/list_days", ["days"       => $result,
                                               "pagination" => Common::getPagination("page-nav", $pageNum, $page, 4)]);

    return $tpl->render();
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

  list($controller, $action) = System::loadController();
  if ($controller) {
    $con = new $controller() or die("Controller not found: " . $controller);
    $con->$action();
  }
</pre>
#### ControllerView.php
<pre>
namespace cons;
use com\Common;
use sys\System;
use thedaysoflife\User;
class ControllerFront extends Controller {
    private $user;
    
    /**
     * Show list of days
     */
    public function ajaxShowDay() {
      $from = (int)$this->post['from'];
      $order = $this->post['order'];
      if ($from > 0) {
        $this->response($this->user->getBestDays($from, $order));
      }
    }
    
    /**
     * Add new comment
     */
    public function ajaxMakeAComment() {
      $comment = ["day_id"     => (int)$this->post['day_id'],
                  "content"    => $this->user->escapeString($this->post['content']),
                  "username"   => $this->user->escapeString($this->post['username']),
                  "email"      => $this->user->escapeString($this->post['email']),
                  "reply_id"   => 0,
                  "reply_name" => '',
                  "like"       => 0,
                  "time"       => time(),
                  "date"       => date('Y-m-d h:i:s'),
                  "ipaddress"  => System::getRealIPaddress(),
                  "session_id" => System::sessionID()];
      $arr = [];
      if ($comment["day_id"] > 0 && $comment["content"] != "" && $comment["username"] != "" && $comment["email"] != "") {
        $re = $this->user->addComment($comment);
        if ($re) {
          $this->user->updateCommentCount($comment["day_id"]);
          $lastCom = $this->user->getLastInsertComment($comment["time"], $comment["session_id"]);
          $arr = ["result"  => true,
                  "day_id"  => $comment["day_id"],
                  "content" => $this->user->getOneCommentHTML($lastCom)];
        }
      } else {
        $arr = ["result" => false, "error" => "Please check inputs"];
      }
      $this->response($arr);
    }
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
