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
    $day   = (int)$para['day'];
    $month = (int)$para['month'];
    $year  = (int)$para['year'];
    $check = checkdate($month, $day, $year);
    if ($check) {
      $title    = $para['title'];
      $content  = $para['content'];
      $username = $para['username'];
      $email    = $para['email'];
      $location = $para['loc'];
      $photos   = $para['photos'];

      $slug     = Com::sanitizeString(strip_tags($title));
      $preview  = $this->view->escapeString($content);
      $preview  = Com::subString($preview, SUMMARY_LENGTH, 3);
      $sanitize = str_replace('-', ' ', Com::sanitizeString($title))
                  . ' ' . str_replace('-', ' ', Com::sanitizeString($username))
                  . ' ' . str_replace('-', ' ', Com::sanitizeString($location))
                  . ' ' . str_replace('-', ' ', Com::sanitizeString($preview));
      //$email		= $Com->_sanitizeString($email);
      $content    = $this->view->escapeString($content);
      $like       = 0;
      $notify     = "no";
      $time       = time();
      $date       = date('Y-m-d h:i:s');
      $ipaddress  = System::getRealIPaddress();
      $session_id = System::sessionID();

      $re = $this->view->addDay($day, $month, $year, $title, $slug, $content, $preview, $sanitize, $username, $email, $location, $notify, $photos, $like, $date, $time, $ipaddress, $session_id);
      if ($re) {
        $row = $this->view->getLastInsertDay($time, $session_id);
        $arr = ["status" => "success",
                "id"     => $row['id'],
                "slug"   => $row['slug'],
                "day"    => $row['day'],
                "month"  => $row['month'],
                "year"   => $row['year']];
        echo(json_encode($arr, JSON_UNESCAPED_SLASHES));
        //          print('{"status": "success","id":"' . $row['id'] . '","slug":"' . $row['slug'] . '","day":"' . $row['day'] . '","month":"' . $row['month'] . '","year":"' . $row['year'] . '"}');
      }
    }
  }
}