<?php
namespace back;
use view\Back;
use core\Admin;

class about extends Back {
  protected $title = "Dashboard :: About";
  protected $contentTemplate = "info";

  public function __construct() {
    parent::__construct();

    $admin              = new Admin();
    $tag                = "about";
    $info               = $admin->getInfoByTag($tag);
    $this->data["tag"]  = $tag;
    $this->data["info"] = $info;
    $this->addHeaderMeta(SITE_URL . "/plugins/ckeditor/ckeditor.js");
  }
}