# Jennifer - A Simple PHP Framework

Jennifer is a simple PHP framework that implements the Ajax MVC (aMVC) pattern. The idea of  aMVC and first pieces of code was written in 2008 when I doing my Master at University of Sydney and taking on job to develope the CMS for Allimport. But only recently I manage to put the code into the framework, and name it Jennifer.

The source code using in this example is the actual code of Thedaysoflife project https://github.com/ngodinhloc/thedaysoflife.com

### Jennifer Framework
- [Ajax MVC Pattern](#ajax-mvc-pattern)
- [The Framework Structure](#the-framework-structure)
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
    - db\driver\MySQL.php
    - db\Database.php
    - view\Base.php
    - tpl\Template.php
    - cons\Controller.php
    - sys\System.php
    - html\HTML.php
    - html\jobject\ClockerPicker.php
    - cache\FileCache.php
    - thedaysoflife\User.php
    - thedaysoflife\Admin.php
- [Views](#views)
    - views/front/index.class.php
- [Controllers](#controllers)
    - index.php
    - ControllerView.php
- [Templates](#templates)
    - front\index.tpl.php
    - jobject\clockpicer.tpl.php
- [Ajax](#ajax)
    - js/ajax.js
    - js/thedaysoflife.front.js

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
#### db\driver\MySQL.php
<pre>
  namespace db\driver;
  use mysqli;
  class MySQL implements DriverInterface {
    /** @var \mysqli **/
    protected $mysqli;
    protected $devMode = true;
    public function __construct($mode) {
      $this->devMode = $mode;
      $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die($this->messages["SERVER_ERROR"]);
    }
    /**
     * Private function query
     * @param string $sql
     * @return \mysqli_result
     */
    public function query($sql = "") {
      $this->isDevMode($sql);
      $result = $this->mysqli->query($sql) or die($this->getErrorMessage($sql));

      return $result;
    }
    ...
  }
</pre>
#### db\Database.php
<pre>
namespace db;
use cache\FileCache;
use db\driver\MySQL;
  abstract class Database implements DatabaseInterface {
    private $devMode = false;
    private $driverName = "MySQL";
    /** @var \db\driver\DriverInterface * */
    private $driver;
    protected $tableName;
    protected $selectCols;
    protected $insertCols;
    protected $insertVals;
    protected $updateVals;
    protected $whereCond;
    protected $orderBy;
    protected $groupBy;
    protected $innerJoin;
    protected $leftJoin;
    protected $rightJoin;
    protected $offset;
    protected $limit;
    protected $result;
    protected $foundRows;
    
    /**
     * Load sqlDriver for this database
     */
    private function loadDriver() {
      switch ($this->driverName) {
        case "MySQL":
        default:
          $this->driver = new MySQL($this->devMode);
          break;
      }
    }

    /**
     * Private function query
     * @param string $sql
     * @return mixed $result
     * @see \db\driver\DriverInterface::query()
     */
    private function query($sql = "") {
      $result = $this->driver->query($sql);

      return $result;
    }

    /**
     * Insert new record
     * $db->table('tbl_day')->columns([col1,col2])->values()->insert()
     * @return mixed
     */
    public function insert() {
      if (!$this->checkTable() || !$this->checkColumns() || !$this->checkValues()) {
        return false;
      }
      $sql = $this->buildQuery(self::QUERY_INSERT);

      return $this->query($sql);
    }

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
      $sql = $this->buildQuery(self::QUERY_SELECT, $foundRows);

      switch ($cache) {
        case "file":
          $data = FileCache::getCache($sql);
          if ($data) {
            $this->result = $data["data"];
            if ($foundRows) {
              $this->foundRows = $data["found"];
            }

            return $this;
          } else {
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

    /**
     * @param string $type
     * @param boolean $foundRows
     * @return string
     */
    private function buildQuery($type, $foundRows = false) {
      switch ($type) {
        case self::QUERY_UPDATE:
          $table = $this->tableName;
          $set = $this->updateVals;
          $where = ($this->whereCond) ? $this->whereCond : "";
          $sql = "UPDATE {$table}{$set} WHERE TRUE {$where}";
          break;
        case self::QUERY_DELETE:
          $table = $this->tableName;
          $where = ($this->whereCond) ? $this->whereCond : "";
          $sql = "DELETE FROM {$table} WHERE TRUE {$where}";
          break;
        case self::QUERY_INSERT:
          $table = $this->tableName;
          $columns = $this->insertCols;
          $values = $this->insertVals;
          $sql = "INSERT INTO {$table}({$columns}) VALUES {$values}";
          break;
        case self::QUERY_SELECT:
          $table = $this->tableName;
          $select = ($foundRows) ? "SELECT SQL_CALC_FOUND_ROWS" : "SELECT";
          $columns = ($this->selectCols) ? $this->selectCols : "*";
          $innerJoin = ($this->innerJoin) ? $this->innerJoin : "";
          $leftJoin = ($this->leftJoin) ? $this->leftJoin : "";
          $rightJoin = ($this->rightJoin) ? $this->rightJoin : "";
          $joins = $innerJoin . $leftJoin . $rightJoin;
          $where = ($this->whereCond) ? "WHERE TRUE " . $this->whereCond : "";
          $groupBy = ($this->groupBy) ? "GROUP BY " . $this->groupBy : "";
          $orderBy = ($this->orderBy) ? "ORDER BY " . $this->orderBy : "";
          $limit = "";
          if (is_numeric($this->limit)) {
            $offset = is_numeric($this->offset) ? $this->offset : 0;
            $limit = " LIMIT {$offset}, {$this->limit}";
          }
          $sql = "{$select} {$columns} FROM {$table} {$joins} {$where} {$groupBy} {$orderBy} {$limit}";
          break;
      }

      return $sql;
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
#### html\jobject\ClockPicker.php
html\jobject is the package of Jquery object classes which use to generate Jquery plugins like clock picer, color picker, date picker, qr code, signature
<pre>
namespace html\jobject;
use html\JObject;

class ClockPicker extends JObject {
  public $metaFiles = [SITE_URL . "/plugins/jquery/clockpicker/bootstrap.clockpicker.min.js",
                       SITE_URL . "/plugins/jquery/clockpicker/bootstrap.clockpicker.min.css",];
  protected $template = "jobject/clockpicker";
  protected $data = ["value" => "", "autoClose" => true];
}
</pre>
#### thedaysoflife\User.php
This is the acutal bussiness class of Thedaysoflife project which handle user activities
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
#### thedaysoflife\Admin.php
This is the acutal bussiness class of Thedaysoflife project which handle admin activities
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
### Views
#### views/front/index.class.php
<pre>
namespace front;
use view\Front;
use thedaysoflife\User;

class index extends Front {
  protected $contentTemplate = "index";

  public function __construct() {
    parent::__construct();

    $user       = new User();
    $days       = $user->getBestDays(0, ORDER_BY_ID);
    $this->data = ["days" => $days, "order" => ORDER_BY_ID];
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
#### cons\ControllerFront.php
<pre>
namespace cons;
use com\Common;
use sys\System;
use thedaysoflife\User;
class ControllerFront extends Controller { 
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
### Templates
#### templates/front/index.tpl.php
This template is a view template of view\front\index.class.php;  $this->data is set in index.class.php(#views)
<pre>
&lt;ul id="slide-show" class="list-unstyled"&gt;
  &lt;?= $this->data["days"] ?&gt;
&lt;/ul&gt;
&lt;div id="show-more" class="show-more" order-tag="&lt;?= $this->data["order"] ?&gt;" data="&lt;?= NUM_PER_PAGE * 2 ?&gt;"&gt;
  + Load More Days
&lt;/div&gt;
&lt;script type="text/javascript"&gt;
  $(function () {
    wookmarkHandle();
  });
&lt;/script&gt;
</pre>
#### templates/jobject/clockpicker.tpl.php
<pre>
&lt;div class="input-group clockpicker" data-autoclose="&lt;?= $this->data["autoClose"] ?>"&gt;
&lt;input type="text" class="form-control &lt;?= $this->class ?&gt;"
     id="&lt;?= $this->id ?&gt;" name="&lt;?= $this->id ?&gt;" value="&lt;?= $this->data["value"] ?&gt;" placeholder="hh:mm"&gt;
&lt;span class="input-group-addon"&gt;&lt;i class="glyphicon glyphicon-time"&gt;&lt;/i&gt;&lt;/span&gt;
&lt;/div&gt;
&lt;script type="text/javascript"&gt;
$(function () {
$('.input-group.clockpicker').clockpicker();
})
&lt;/script&gt;
</pre>
### Ajax
#### js/ajax.js
<pre>
/**
 * @param actionPara object {"action":action, "controller":controller}
 * @param para object $.para({"name":value})
 * @param loader string
 * @param containerPara array {"container" : container_id, "act": "replace|append"]
 * @param callback function
 */
function ajaxAction(actionPara, para, loader, containerPara, callback) {
  para = para + "&" + $.param(actionPara);
  if (loader) {
    $(loader).html(AJAX_LOADER);
  }
  $.ajax({
    url:     CONST.CONTROLLER_URL,
    type:    "POST",
    cache:   false,
    data:    para,
    success: function (data, textStatus, jqXHR) {
      if (loader) {
        $(loader).html('');
      }
      if (callback) {
        callback(data);
        return;
      }
      if (containerPara) {
        container = containerPara.container;
        act = containerPara.act;
        if (act == "replace") {
          $(container).html(data);
        }
        if (act == "append") {
          $(container).append(data);
        }
      }
    },
    error:   function (jqXHR, textStatus, errorThrown) {
    }
  });
}
</pre>
#### js/thedaysoflife.front.js
<pre>
/**
 * Add new day
 */
function ajaxMakeADay() {
  content = $("#div-day-content").find("select[name], textarea[name], input[name]").serialize();
  info = $("#div-author-info").find("select[name], textarea[name], input[name]").serialize();
  photos = getIDs();
  data = content + "&" + info + "&" + $.param({"photos": photos});
  callback = processMakeADay;
  ajaxAction({"action": "ajaxMakeADay", "controller": "ControllerFront"}, data, "#ajax-loader", false, callback);
}

/**
 * process returned data when add day
 * @param data
 */
function processMakeADay(data) {
  var getData = $.parseJSON(data);
  if (getData.status = "success") {
    link = CONST.LIST_URL + getData.id + "/" + getData.day + getData.month +
           getData.year + '-' + getData.slug + CONST.LIST_EXT;
    window.location = link;
  }
}
</pre>
