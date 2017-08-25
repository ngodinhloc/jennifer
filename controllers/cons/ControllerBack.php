<?php
namespace cons;

use com\Common;
use controller\Controller;
use thedaysoflife\model\Admin;

class ControllerBack extends Controller {
  protected $requiredPermission = ["admin"];
  /** @var Admin */
  private $admin;

  public function __construct() {
    parent::__construct();
    $this->admin = new Admin();
  }

  /**
   * Remove a day
   */
  public function ajaxRemoveADay() {
    $id = (int)$this->post['id'];
    $re = $this->admin->removeDay($id);
    if ($re) {
      $array = ["status" => "success", "id" => $id];
      $this->response($array);
    }
  }

  /**
   * Print day list
   */
  public function ajaxPrintDay() {
    $page = (int)$this->post['page'] == 0 ? 1 : (int)$this->post['page'];
    $this->response($this->admin->getDayList($page));
  }

  /**
   * Update a day
   */
  public function ajaxUpdateADay() {
    $day          = [];
    $day["id"]    = (int)$this->post['id'];
    $day["day"]   = (int)$this->post['day'];
    $day["month"] = (int)$this->post['month'];
    $day["year"]  = (int)$this->post['year'];
    $check        = checkdate($day["month"], $day["day"], $day["year"]);
    if ($check) {
      $day["title"]    = $this->admin->escapeString($this->post['title']);
      $day["slug"]     = Common::sanitizeString(($day["title"]));
      $day["content"]  = $this->admin->escapeString($this->post['content'], true);
      $day["username"] = $this->admin->escapeString($this->post['username']);
      $day["email"]    = $this->admin->escapeString($this->post['email']);
      $day["location"] = $this->admin->escapeString($this->post['loc']);
      $day["photos"]   = $this->admin->escapeString($this->post['photos']);
      $day["like"]     = (int)$this->post['like'];
      $day["preview"]  = Common::subString($day["content"], SUMMARY_LENGTH, 3);
      $day["sanitize"] = str_replace('-', ' ', Common::sanitizeString($day["title"]))
                         . ' ' . str_replace('-', ' ', Common::sanitizeString($day["username"]))
                         . ' ' . str_replace('-', ' ', Common::sanitizeString($day["location"]))
                         . ' ' . str_replace('-', ' ', Common::sanitizeString($day["preview"]));
      $re              = $this->admin->updateDay($day);
      if ($re) {
        $array = ["status" => "success",
                  "id"     => $day["id"],
                  "slug"   => $day["slug"],
                  "day"    => $day["day"],
                  "month"  => $day["month"],
                  "year"   => $day["year"]];
        $this->response($array);
      }
    }
    else {
      $array = ["status" => "failed", "id" => $day["id"]];
      $this->response($array);
    }
  }

  /**
   * Update site info: about, privacy
   */
  public function ajaxUpdateInfo() {
    $tag     = $this->admin->escapeString($this->post['tag']);
    $title   = $this->admin->escapeString($this->post['title']);
    $content = $this->admin->escapeString($this->post['content'], true);
    if ($tag != "") {
      $result = $this->admin->updateInfo($tag, $title, $content);
      if ($result) {
        $this->response("Info updated.");
      }
    }
  }

  /**
   * Remove unused photos
   */
  public function ajaxRemoveUnusedPhoto() {
    $result = $this->admin->removeUnusedPhotos();
    $this->response($result);
  }

  /**
   * Check, analyse, repair database
   */
  public function ajaxCheckDatabase() {
    $act    = $this->admin->escapeString($this->post['act']);
    $result = $this->admin->checkDatabaseTables($act);
    $this->response($result);
  }
}