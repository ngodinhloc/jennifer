<?php
namespace cons;

use com\Com;
use core\Admin;
use cons\Controller;

class ControllerAdmin extends Controller {
  protected $requiredPermission = ["admin"];
  private $admin;

  public function __construct() {
    parent::__construct();

    $this->admin = new Admin();
  }

  public function ajaxRemoveADay($para) {
    $id = (int)$para['id'];
    $re = $this->admin->removeDay($id);
    if ($re) {
      $array = ["status" => "success", "id" => $id];
      echo json_encode($array);
    }
  }

  public function ajaxPrintDay($para) {
    $page = (int)$para['page'];
    if ($page == 0) {
      $page = 1;
    }
    echo($this->admin->getDayList($page));
  }

  public function ajaxUpdateADay($para) {
    $id    = (int)$para['id'];
    $day   = (int)$para['day'];
    $month = (int)$para['month'];
    $year  = (int)$para['year'];
    $check = checkdate($month, $day, $year);
    if ($check) {
      $title    = $para['title'];
      $slug     = Com::sanitizeString(strip_tags($title));
      $content  = $para['content'];
      $username = $para['username'];
      $email    = $para['email'];
      $location = $para['loc'];
      $photos   = $para['photos'];
      $like     = (int)$para['like'];
      $preview  = $this->admin->escapeString($content);
      $preview  = Com::subString($preview, SUMMARY_LENGTH, 3);
      $sanitize = str_replace('-', ' ', Com::sanitizeString($title))
                  . ' ' . str_replace('-', ' ', Com::sanitizeString($username))
                  . ' ' . str_replace('-', ' ', Com::sanitizeString($location))
                  . ' ' . str_replace('-', ' ', Com::sanitizeString($preview));
      //$email		= $Com->_sanitizeString($email);
      $re = $this->admin->updateDay($id, $day, $month, $year, $title, $slug, $content, $preview, $sanitize, $photos, $username, $email, $location, $like);
      if ($re) {
        $array = ["status" => "success", "id" => $id, "slug" => $slug, "day" => $day, "month" => $month,
                  "year"   => $year];
        echo json_encode($array);
      }
    }
    else {
      $array = ["status" => "failed", "id" => $id];
      echo json_encode($array);
    }
  }

  public function ajaxUpdateInfo($para) {
    $tag     = trim($para['tag']);
    $title   = trim($para['title']);
    $content = trim($para['content']);
    if ($tag != "") {
      $re = $this->admin->updateInfo($tag, $title, $content);
      if ($re) {
        echo("Info updated.");
      }
    }
  }

  public function ajaxRemoveUnusedPhoto($para) {
    $this->admin->removeUnusedPhotos();
  }

  public function ajaxCheckDatabase($para) {
    $act = $para['act'];
    if ($act != "") {
      $re = $this->admin->checkDatabaseTables($act);
      echo $re;
    }
  }
}