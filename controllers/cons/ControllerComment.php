<?php
namespace cons;

use com\Com;
use core\View;
use sys\System;

class ControllerComment extends Controller {
  private $view;

  public function __construct() {
    parent::__construct();

    $this->view = new View();
  }

  public function ajaxMakeAComment($para) {
    $day_id     = (int)$para['day_id'];
    $content    = trim($para['content']);
    $username   = trim($para['username']);
    $email      = trim($para['email']);
    $email      = Com::sanitizeString($email);
    $reply_id   = 0;
    $reply_name = '';
    $like       = 0;
    $time       = time();
    $date       = date('Y-m-d h:i:s');
    $ipaddress  = System::getRealIPaddress();
    $session_id = System::sessionID();
    $arr        = [];

    if ($day_id > 0 && $content != "" && $username != "" && $email != "") {
      $re = $this->view->addComment($day_id, $content, $username, $email, $reply_id, $reply_name, $like, $date, $time, $ipaddress, $session_id);
      if ($re) {
        $this->view->updateCommentCount($day_id);
        $last_com = $this->view->getLastInsertComment($time, $session_id);
        $arr      = ["result" => true, "day_id" => $day_id, "content" => $this->view->getOneCommentHTML($last_com)];
      }
    }
    else {
      $arr = ["result" => false, "error" => "Please check inputs"];
    }
    echo(json_encode($arr, JSON_UNESCAPED_SLASHES));
  }

  public function ajaxMakeAReply($para) {
    $day_id     = (int)$para['day_id'];
    $com_id     = (int)$para['com_id'];
    $content    = trim($para['content']);
    $username   = trim($para['username']);
    $email      = trim($para['email']);
    $email      = Com::sanitizeString($email);
    $reply_id   = (int)$para['rep_id'];
    $reply_name = trim($para['rep_name']);
    $like       = 0;
    $time       = time();
    $date       = date('Y-m-d h:i:s');
    $ipaddress  = System::getRealIPaddress();
    $session_id = System::sessionID();
    $arr        = [];

    if ($day_id > 0 && $content != "" && $username != "" && $email != "" && $reply_id > 0) {
      $re = $this->view->addComment($day_id, $content, $username, $email, $reply_id, $reply_name, $like, $date, $time, $ipaddress, $session_id);
      if ($re) {
        $this->view->updateCommentCount($day_id);
        $lastCom = $this->view->getLastInsertComment($time, $session_id);
        $arr     = ["result" => true, "com_id" => $com_id, "content" => $this->view->getOneCommentHTML($lastCom)];
      }
    }
    else {
      $arr = ["result" => false, "error" => "Please check inputs"];
    }
    echo(json_encode($arr, JSON_UNESCAPED_SLASHES));
  }

  public function ajaxLikeADay($para) {
    $id = (int)$para['id'];
    if ($id > 0) {
      $ipaddress = trim(System::getTodayIPaddress());
      $this->view->updateLikeDay($id, $ipaddress);
    }
  }

  public function ajaxLikeAComment($para) {
    $id = (int)$para['id'];
    if ($id > 0) {
      $ipaddress = trim(System::getTodayIPaddress());
      $this->view->updateLikeComment($id, $ipaddress);
    }
  }

  public function ajaxDislikeAComment($para) {
    $id = (int)$para['id'];
    if ($id > 0) {
      $ipaddress = trim(System::getTodayIPaddress());
      $this->view->updateDislikeComment($id, $ipaddress);
    }
  }
}