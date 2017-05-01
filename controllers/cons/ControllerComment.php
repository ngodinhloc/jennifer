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
    $comment = ["day_id"     => (int)$para['day_id'],
                "content"    => $this->view->escapeString($para['content']),
                "username"   => $this->view->escapeString($para['username']),
                "email"      => $this->view->escapeString($para['email']),
                "reply_id"   => 0,
                "reply_name" => '',
                "like"       => 0,
                "time"       => time(), "date" => date('Y-m-d h:i:s'),
                "ipaddress"  => System::getRealIPaddress(),
                "session_id" => System::sessionID()];
    $arr     = [];
    if ($comment["day_id"] > 0 && $comment["content"] != "" && $comment["username"] != "" && $comment["email"] != "") {
      $re = $this->view->addComment($comment);
      if ($re) {
        $this->view->updateCommentCount($comment["day_id"]);
        $lastCom = $this->view->getLastInsertComment($comment["time"], $comment["session_id"]);
        $arr     = ["result"  => true, "day_id" => $comment["day_id"],
                    "content" => $this->view->getOneCommentHTML($lastCom)];
      }
    }
    else {
      $arr = ["result" => false, "error" => "Please check inputs"];
    }
    $this->response($arr);
  }

  public function ajaxMakeAReply($para) {
    $comment = ["day_id"     => (int)$para['day_id'],
                "com_id"     => (int)$para['com_id'],
                "content"    => $this->view->escapeString($para['content']),
                "username"   => $this->view->escapeString($para['username']),
                "email"      => $this->view->escapeString($para['email']),
                "reply_id"   => (int)$para['rep_id'],
                "reply_name" => $this->view->escapeString($para['rep_name']),
                "like"       => 0,
                "time"       => time(),
                "date"       => date('Y-m-d h:i:s'),
                "ipaddress"  => System::getRealIPaddress(),
                "session_id" => System::sessionID(),];
    $arr     = [];

    if ($comment["day_id"] > 0 && $comment["content"] != "" && $comment["username"] != "" && $comment["email"] != "" &&
        $comment["reply_id"] > 0
    ) {
      $re = $this->view->addComment($comment);
      if ($re) {
        $this->view->updateCommentCount($comment["day_id"]);
        $lastCom = $this->view->getLastInsertComment($comment["time"], $comment["session_id"]);
        $arr     = ["result"  => true, "com_id" => $comment["com_id"],
                    "content" => $this->view->getOneCommentHTML($lastCom)];
      }
    }
    else {
      $arr = ["result" => false, "error" => "Please check inputs"];
    }
    $this->response($arr);
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