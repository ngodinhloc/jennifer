<?php
namespace back;

use jennifer\db\driver\MySQL;
use jennifer\html\Element;
use jennifer\view\ViewInterface;
use thedaysoflife\view\ViewBack;

class tools extends ViewBack implements ViewInterface {
  protected $title = "Dashboard :: Tools";
  protected $contentTemplate = "tools";
  protected $requiredPermission = ["admin"];

  public function __construct() {
    parent::__construct();
  }

  public function prepare() {
    $databaseTools = Element::radios(MySQL::DB_ACTIONS, "checkdb", null, null, "<br>");
    $photoTools    = Element::radios(["REMOVE_UNUSED" => "Remove unused photos"], "photoTools", "REMOVE_UNUSED", null, "");
    $this->data    = ["photoTools"    => $photoTools,
                      "databaseTools" => $databaseTools];
  }
}