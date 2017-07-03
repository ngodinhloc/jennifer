<?php
  namespace back;

  use com\Common;
  use db\driver\MySQL;
  use view\Back;

  class tools extends Back {
    protected $title = "Dashboard :: Tools";
    protected $contentTemplate = "tools";
    protected $requiredPermission = ["admin"];

    public function __construct() {
      parent::__construct();
      $databaseTools = Common::arrayToRadios(MySQL::DB_ACTIONS, "checkdb", null, null, "<br>");
      $photoTools = Common::arrayToRadios(["REMOVE_UNUSED" => "Remove unused photos"], "photoTools", "REMOVE_UNUSED", null, "");
      $this->data = ["photoTools"    => $photoTools,
                     "databaseTools" => $databaseTools];
    }
  }