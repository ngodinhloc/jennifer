<?php
namespace cons;

use com\Com;
use core\View;
use sys\System;

class ControllerMake extends Controller {
  private $view;

  public function __construct() {
    parent::__construct();

    $this->view = new View();
  }

  public function ajaxMakeADay($para) {
    $day          = [];
    $day["day"]   = (int)$para['day'];
    $day["month"] = (int)$para['month'];
    $day["year"]  = (int)$para['year'];
    $check        = checkdate($day["month"], $day["day"], $day["year"]);
    if ($check) {
      $day["title"]      = $this->view->escapeString($para['title']);
      $day["content"]    = $this->view->escapeString($para['content']);
      $day["username"]   = $this->view->escapeString($para['username']);
      $day["email"]      = $this->view->escapeString($para['email']);
      $day["location"]   = $this->view->escapeString($para['loc']);
      $day["photos"]     = $this->view->escapeString($para['photos']);
      $day["slug"]       = Com::sanitizeString($day["title"]);
      $day["preview"]    = Com::subString($day["content"], SUMMARY_LENGTH, 3);
      $day["sanitize"]   = str_replace('-', ' ', Com::sanitizeString($day["title"]))
                           . ' ' . str_replace('-', ' ', Com::sanitizeString($day["username"]))
                           . ' ' . str_replace('-', ' ', Com::sanitizeString($day["location"]))
                           . ' ' . str_replace('-', ' ', Com::sanitizeString($day["preview"]));
      $day["like"]       = 0;
      $day["notify"]     = "no";
      $day["time"]       = time();
      $day["date"]       = date('Y-m-d h:i:s');
      $day["ipaddress"]  = System::getRealIPaddress();
      $day["session_id"] = System::sessionID();

      $re = $this->view->addDay($day);
      if ($re) {
        $row = $this->view->getLastInsertDay($day["time"], $day["session_id"]);
        $arr = ["status" => "success", "id" => $row['id'], "slug" => $row['slug'], "day" => $row['day'],
                "month"  => $row['month'], "year" => $row['year']];
        $this->response($arr);
      }
    }
  }
}