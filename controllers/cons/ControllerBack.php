<?php
namespace cons;

use jennifer\com\Common;
use jennifer\controller\Controller;
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
   * @return array
   */
  public function ajaxRemoveADay() {
    $id = (int)$this->post['id'];
    $re = $this->admin->removeDay($id);
    if ($re) {
      $this->result = ["status" => "success", "id" => $id];
    }

    return $this->result;
  }

  /**
   * Print day list
   * @return string
   */
  public function ajaxPrintDay() {
    $page         = (int)$this->post['page'] == 0 ? 1 : (int)$this->post['page'];
    $this->result = $this->admin->getDayList($page);

    return $this->result;
  }

  /**
   * Update a day
   * @return array
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
        $this->result = ["status" => "success",
                         "id"     => $day["id"],
                         "slug"   => $day["slug"],
                         "day"    => $day["day"],
                         "month"  => $day["month"],
                         "year"   => $day["year"]];
      }
    }
    else {
      $this->result = ["status" => "failed", "id" => $day["id"]];
    }

    return $this->result;
  }

  /**
   * Update site info: about, privacy
   * @return string
   */
  public function ajaxUpdateInfo() {
    $tag     = $this->admin->escapeString($this->post['tag']);
    $title   = $this->admin->escapeString($this->post['title']);
    $content = $this->admin->escapeString($this->post['content'], true);
    if ($tag != "") {
      $result = $this->admin->updateInfo($tag, $title, $content);
      if ($result) {
        $this->result = "Info updated.";
      }
    }

    return $this->result;
  }

  /**
   * Remove unused photos
   * @return string
   */
  public function ajaxRemoveUnusedPhoto() {
    $this->result = $this->admin->removeUnusedPhotos();

    return $this->result;
  }

  /**
   * Check, analyse, repair database
   * @return string
   */
  public function ajaxCheckDatabase() {
    $act          = $this->admin->escapeString($this->post['act']);
    $this->result = $this->admin->checkDatabaseTables($act);

    return $this->result;
  }
}