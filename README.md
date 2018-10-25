# Jennifer - A Simple PHP Framework

Jennifer is a simple PHP framework that implements the Ajax MVC pattern. The idea of  aMVC and first pieces of code was written in 2008 when I doing my Master at University of Sydney and taking on job to develope the CMS for Allimport. But only recently I manage to put the code into the framework, and name it Jennifer.

For example of usage please take a look at Thedaysoflife project https://github.com/ngodinhloc/thedaysoflife.com which was developed using Jennifer framework

### Jennifer Framework

- [Models](#models)
    - jennifer\db\driver\MySQL.php
    - jennifer\db\Database.php
    - jennifer\template\Template.php
    - jennifer\controller\Controller.php
    - jennifer\sys\System.php
    
### Models
#### jennifer\db\driver\MySQL.php
<pre>
namespace jennifer\db\driver;

use jennifer\exception\DBException;
use jennifer\sys\Config;
use mysqli;

class MySQL implements DriverInterface {
  /** @var \mysqli * */
  protected $mysqli;
  protected $devMode = true;
  private $messages = [
    "SERVER_ERROR" => "Could not connect to MySQL server",
    "QUERY_ERROR"  => "Error occurs when trying to query MySQL database",
  ];
  const DB_ACTIONS = ["CHECK"    => "CHECK TABLE",
                      "ANALYZE"  => "ANALYZE TABLE",
                      "REPAIR"   => "REPAIR TABLE",
                      "OPTIMIZE" => "OPTIMIZE TABLE",];

  public function __construct($mode) {
    $this->devMode = $mode;
    $this->mysqli = new mysqli(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, Config::DB_NAME) or
    die($this->messages["SERVER_ERROR"]);
  }

  public function __destruct() {
    $this->mysqli->close();
  }

  /**
   * Private function query
   * @param string $sql
   * @return \mysqli_result
   * @throws DBException
   */
  public function query($sql = "") {
    $this->isDevMode($sql);
    $result = $this->mysqli->query($sql) or die($this->getErrorMessage($sql));

    return $result;
  }

  /**
   * Get found rows from the most recent query
   * @return int
   */
  public function getFoundRows() {
    $sql       = "SELECT FOUND_ROWS()";
    $result    = $this->query($sql);
    $foundRows = $result->fetch_row();

    return $foundRows[0];
  }
}
</pre>
#### jennifer\db\Database.php
<pre>
namespace jennifer\db;

use jennifer\cache\CacheInterface;
use jennifer\cache\FileCache;
use jennifer\db\driver\DriverFactory;

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
#### jennifer\template\Template.php
<pre>
namespace jennifer\template;

use jennifer\com\Compressor;
use jennifer\sys\Config;

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
#### jennifer\controller\Controller.php
<pre>
/**
 * The base controller class: all controller will extend this class
 * Each public function of controller class is an action
 */
namespace namespace jennifer\controller;

use jennifer\auth\Authentication;
use jennifer\http\Request;
use jennifer\io\Output;

class Controller implements ControllerInterface {
    /** @var Authentication */
      protected $authentication;
      /** @var  Request */
      protected $request;
      /** @var Output */
      protected $output;
      /** @var array|bool usr data */
      protected $userData = false;
      /** @var bool|array required permission */
      protected $requiredPermission = false;
      /** @var mixed result of the action */
      protected $result;
    
      const ERROR_CODE_CONTROLLER_NOT_FOUND = 1;
      const ERROR_CODE_ACTION_NOT_FOUND     = 2;
    
      public function __construct() {
        $this->request        = new Request();
        $this->authentication = new Authentication();
        $this->output         = new Output();
        $this->authentication->checkUserPermission($this->requiredPermission, "controller");
        $this->userData = $this->authentication->getUserData();
      }
    
      /**
       * Run the action
       * @param string $action public action (method) name
       */
      public function action($action) {
        if (method_exists($this, $action)) {
          $result = $this->$action();
          $this->response($result, $this->request->post["json"]);
        }
    
        $this->error(self::ERROR_CODE_ACTION_NOT_FOUND);
      }
    
      /**
       * Controller response
       * @param array|string $data
       * @param bool $json
       * @param int $jsonOpt
       */
      protected function response($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES) {
        $this->output->ajax($data, $json, $jsonOpt);
      }
}
</pre>
#### jennifer\sys\System.php
<pre>
namespace jennifer/sys;
class System {
    /** @var Config */
      public $config;
      /** @var  ViewInterface */
      protected $viewFactory;
      /** @var  ControllerFactory */
      protected $controllerFactory;
      /** @var  Request */
      protected $request;
      protected $view;
      protected $controller;
      protected $action;
      protected $routing;
    
      public function __construct(Config $config = null, ViewFactory $viewFactory = null,
                                  ControllerFactory $controllerFactory = null) {
        $this->config            = $config ? $config : new Config();
        $this->routing           = $this->config->getRouting();
        $this->viewFactory       = $viewFactory ? $viewFactory : new ViewFactory();
        $this->controllerFactory = $controllerFactory ? $controllerFactory : new ControllerFactory();
        $this->request           = new Request();
      }
    
      /**
       * Render view
       */
      public function renderView() {
        if ($this->view) {
          $view = $this->viewFactory->createView($this->view);
          $view->prepare();
          $view->render();
        }
      }
    
      /**
       * Run the controller
       */
      public function runController() {
        if ($this->controller && $this->action) {
          $controller = $this->controllerFactory->createController($this->controller);
          $controller->action($this->action);
        }
      }
}
</pre>