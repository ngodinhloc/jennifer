<?php
namespace back;

use core\Admin;
use view\Back;

class day extends Back {
  protected $title = "Dashboard :: Edit";
  protected $contentTemplate = "edit";

  public function __construct() {
    parent::__construct();

    $id = $this->hasPara("day");
    if ($id) {
      $admin      = new Admin();
      $row        = $admin->getDayById($id);
      $this->data = ["row" => $row];
      $this->addMetaFile(SITE_URL . "/plugins/ckeditor/ckeditor.js");
    }
  }
}