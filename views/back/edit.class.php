<?php
namespace back;
use view\Back;
use core\Admin;
use sys\System;

class edit extends Back {
  protected $title = "Dashboard :: Edit";
  protected $contentTemplate = "edit";

  public function __construct() {
    parent::__construct();

    $admin = new Admin();
    $id    = System::getViewPara("day");
    if ($id) {
      $row               = $admin->getDayById($id);
      $this->data["id"]  = $id;
      $this->data["row"] = $row;
      $this->addHeaderMeta(SITE_URL . "/plugins/ckeditor/ckeditor.js");
    }
  }
}