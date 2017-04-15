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
    $day          = [];
    $day["id"]    = (int)$para['id'];
    $day["day"]   = (int)$para['day'];
    $day["month"] = (int)$para['month'];
    $day["year"]  = (int)$para['year'];
    $check        = checkdate($day["month"], $day["day"], $day["year"]);
    if ($check) {
      $day["title"]    = $this->admin->escapeString($para['title']);
      $day["slug"]     = Com::sanitizeString(($day["title"]));
      $day["content"]  = $this->admin->escapeString($para['content'], true);
      $day["username"] = $this->admin->escapeString($para['username']);
      $day["email"]    = $this->admin->escapeString($para['email']);
      $day["location"] = $this->admin->escapeString($para['loc']);
      $day["photos"]   = $this->admin->escapeString($para['photos']);
      $day["like"]     = (int)$para['like'];
      $day["preview"]  = Com::subString($day["content"], SUMMARY_LENGTH, 3);
      $day["sanitize"] = str_replace('-', ' ', Com::sanitizeString($day["title"]))
                         . ' ' . str_replace('-', ' ', Com::sanitizeString($day["username"]))
                         . ' ' . str_replace('-', ' ', Com::sanitizeString($day["location"]))
                         . ' ' . str_replace('-', ' ', Com::sanitizeString($day["preview"]));
      $re              = $this->admin->updateDay($day);
      if ($re) {
        $array = ["status" => "success", "id" => $day["id"], "slug" => $day["slug"], "day" => $day["day"],
                  "month"  => $day["month"], "year" => $day["year"]];
        echo json_encode($array);
      }
    }
    else {
      $array = ["status" => "failed", "id" => $day["id"]];
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