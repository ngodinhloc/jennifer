<?php
namespace view;
use view\Base;
use sys\System;

class Back extends Base {
  protected $headerTemplate = "_header";
  protected $footerTemplate = "_footer";
  protected $requiredPermission = ["admin"];

  public function __construct() {
    parent::__construct();

    if ($this->view != DEFAULT_VIEW) {
      $user = System::checkJWT();
      if (!$user) {
        System::redirectTo("back/");
      }
      $this->data["user"] = $user;
    }
  }
}