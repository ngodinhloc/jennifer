<?php
namespace back;
require_once(DOC_ROOT . '/plugins/facebook/src/facebook.php');
use view\Back;
use core\Admin;
use Facebook;

class days extends Back {
  protected $title = "Dashboard :: List daysl";
  protected $contentTemplate = "days";

  public function __construct() {
    parent::__construct();

    $fb                 = new Facebook(['appId' => FB_APPID, 'secret' => FB_SECRET]);
    $fbUser             = $fb->getUser();
    $admin              = new Admin();
    $days               = $admin->getDayList(1);
    $this->data["days"] = $days;
  }
}