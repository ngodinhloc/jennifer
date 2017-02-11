<?php
namespace back;
use view\Back;
use core\Admin;

class privacy extends Back {
  protected $title = "Dashboard :: Privacy";
  protected $contentTemplate = "info";

  public function __construct() {
    parent::__construct();

    $admin              = new Admin();
    $tag                = "privacy";
    $info               = $admin->getInfoByTag($tag);
    $this->data["tag"]  = $tag;
    $this->data["info"] = $info;
    $this->addHeaderMeta(SITE_URL . "/plugins/ckeditor/ckeditor.js");
  }
}