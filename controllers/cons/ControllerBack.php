<?php

namespace cons;

use jennifer\com\Common;
use jennifer\controller\Controller;
use thedaysoflife\model\Admin;
use thedaysoflife\sys\Configs;

class ControllerBack extends Controller {
  protected $requiredPermission = ["admin"];
  /** @var Admin */
  private $admin;

  public function __construct(Admin $admin = null) {
    parent::__construct();
    $this->admin = $admin ? $admin : new Admin();
  }

  /**
   * Remove a day
   * @return array
   */
  public function ajaxRemoveADay() {
    $id = (int)$this->request->post['id'];
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
    $page         = (int)$this->request->post['page'] == 0 ? 1 : (int)$this->request->post['page'];
    $this->result = $this->admin->getDayList($page);

    return $this->result;
  }

  /**
   * Update a day
   * @return array
   */
  public function ajaxUpdateADay() {
    $day          = [];
    $day["id"]    = (int)$this->request->post['id'];
    $day["day"]   = (int)$this->request->post['day'];
    $day["month"] = (int)$this->request->post['month'];
    $day["year"]  = (int)$this->request->post['year'];
    $check        = checkdate($day["month"], $day["day"], $day["year"]);
    if ($check) {
      $day["title"]    = $this->request->post['title'];
      $day["slug"]     = Common::sanitizeString(($day["title"]));
      $day["content"]  = $this->request->post['content'];
      $day["username"] = $this->request->post['username'];
      $day["email"]    = $this->request->post['email'];
      $day["location"] = $this->request->post['loc'];
      $day["photos"]   = $this->request->post['photos'];
      $day["like"]     = (int)$this->request->post['like'];
      $day["preview"]  = Common::subString($day["content"], Configs::SUMMARY_LENGTH, 3);
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
    $tag     = $this->request->post['tag'];
    $title   = $this->request->post['title'];
    $content = $this->request->post['content'];
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
    $act          = $this->request->post['act'];
    $this->result = $this->admin->checkDatabaseTables($act);

    return $this->result;
  }
}