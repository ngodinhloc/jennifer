<?php
namespace back;

use com\Common;
use db\driver\MySQL;
use thedaysoflife\view\ViewBack;
use view\ViewInterface;

class tools extends ViewBack implements ViewInterface {
  protected $title = "Dashboard :: Tools";
  protected $contentTemplate = "tools";
  protected $requiredPermission = ["admin"];

  public function __construct() {
    parent::__construct();
  }

  public function prepare() {
    $databaseTools = Common::arrayToRadios(MySQL::DB_ACTIONS, "checkdb", null, null, "<br>");
    $photoTools    = Common::arrayToRadios(["REMOVE_UNUSED" => "Remove unused photos"], "photoTools", "REMOVE_UNUSED", null, "");
    $this->data    = ["photoTools"    => $photoTools,
                      "databaseTools" => $databaseTools];
  }
}