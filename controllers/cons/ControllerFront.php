<?php
  namespace cons;

  use com\Com;
  use thedaysoflife\View;
  use sys\System;

  class ControllerFront extends Controller {
    private $view;

    public function __construct() {
      parent::__construct();

      $this->view = new View();
    }

    /**
     * Add new day
     */
    public function ajaxMakeADay() {
      $day = [];
      $day["day"] = (int)$this->post['day'];
      $day["month"] = (int)$this->post['month'];
      $day["year"] = (int)$this->post['year'];
      $check = checkdate($day["month"], $day["day"], $day["year"]);
      if ($check) {
        $day["title"] = $this->view->escapeString($this->post['title']);
        $day["content"] = $this->view->escapeString($this->post['content']);
        $day["username"] = $this->view->escapeString($this->post['username']);
        $day["email"] = $this->view->escapeString($this->post['email']);
        $day["location"] = $this->view->escapeString($this->post['loc']);
        $day["photos"] = $this->view->escapeString($this->post['photos']);
        $day["slug"] = Com::sanitizeString($day["title"]);
        $day["preview"] = Com::subString($day["content"], SUMMARY_LENGTH, 3);
        $day["sanitize"] = str_replace('-', ' ', Com::sanitizeString($day["title"]))
                           . ' ' . str_replace('-', ' ', Com::sanitizeString($day["username"]))
                           . ' ' . str_replace('-', ' ', Com::sanitizeString($day["location"]))
                           . ' ' . str_replace('-', ' ', Com::sanitizeString($day["preview"]));
        $day["like"] = 0;
        $day["notify"] = "no";
        $day["time"] = time();
        $day["date"] = date('Y-m-d h:i:s');
        $day["ipaddress"] = System::getRealIPaddress();
        $day["session_id"] = System::sessionID();

        $re = $this->view->addDay($day);
        if ($re) {
          $row = $this->view->getLastInsertDay($day["time"], $day["session_id"]);
          $arr = ["status" => "success",
                  "id"     => $row['id'],
                  "slug"   => $row['slug'],
                  "day"    => $row['day'],
                  "month"  => $row['month'],
                  "year"   => $row['year']];
          $this->response($arr);
        }
      }
    }

    /**
     * Show list of days
     */
    public function ajaxShowDay() {
      $from = (int)$this->post['from'];
      $order = $this->post['order'];
      if ($from > 0) {
        $this->response($this->view->getBestDays($from, $order));
      }
    }

    /**
     * Search days
     */
    public function ajaxSearchDay() {
      $search = trim($this->post['search']);
      if ($search != "") {
        $this->response($this->view->getSearch($search));
      }
    }

    /**
     * Search more days (click on show more on search)
     */
    public function ajaxSearchMore() {
      $search = trim($this->post['search']);
      $from = (int)$this->post['from'];
      if ($search != "" && $from > 0) {
        $this->response($this->view->getSearchMore($search, $from));
      }
    }

    /**
     * Show calendar
     */
    public function ajaxShowCalendar() {
      $from = (int)$this->post['from'];
      if ($from > 0) {
        $this->response($this->view->getCalendar($from));
      }
    }

    /**
     * Show pictures
     */
    public function ajaxShowPicture() {
      $from = (int)$this->post['from'];
      if ($from > 0) {
        $this->response($this->view->getPicture($from));
      }
    }

    /**
     * Add new comment
     */
    public function ajaxMakeAComment() {
      $comment = ["day_id"     => (int)$this->post['day_id'],
                  "content"    => $this->view->escapeString($this->post['content']),
                  "username"   => $this->view->escapeString($this->post['username']),
                  "email"      => $this->view->escapeString($this->post['email']),
                  "reply_id"   => 0,
                  "reply_name" => '',
                  "like"       => 0,
                  "time"       => time(),
                  "date"       => date('Y-m-d h:i:s'),
                  "ipaddress"  => System::getRealIPaddress(),
                  "session_id" => System::sessionID()];
      $arr = [];
      if ($comment["day_id"] > 0 && $comment["content"] != "" && $comment["username"] != "" && $comment["email"] != "") {
        $re = $this->view->addComment($comment);
        if ($re) {
          $this->view->updateCommentCount($comment["day_id"]);
          $lastCom = $this->view->getLastInsertComment($comment["time"], $comment["session_id"]);
          $arr = ["result"  => true,
                  "day_id"  => $comment["day_id"],
                  "content" => $this->view->getOneCommentHTML($lastCom)];
        }
      } else {
        $arr = ["result" => false, "error" => "Please check inputs"];
      }
      $this->response($arr);
    }

    /**
     * Add a reply (to comment)
     */
    public function ajaxMakeAReply() {
      $comment = ["day_id"     => (int)$this->post['day_id'],
                  "com_id"     => (int)$this->post['com_id'],
                  "content"    => $this->view->escapeString($this->post['content']),
                  "username"   => $this->view->escapeString($this->post['username']),
                  "email"      => $this->view->escapeString($this->post['email']),
                  "reply_id"   => (int)$this->post['rep_id'],
                  "reply_name" => $this->view->escapeString($this->post['rep_name']),
                  "like"       => 0,
                  "time"       => time(),
                  "date"       => date('Y-m-d h:i:s'),
                  "ipaddress"  => System::getRealIPaddress(),
                  "session_id" => System::sessionID(),];
      $arr = [];

      if ($comment["day_id"] > 0 && $comment["content"] != "" && $comment["username"] != "" && $comment["email"] != "" &&
          $comment["reply_id"] > 0
      ) {
        $re = $this->view->addComment($comment);
        if ($re) {
          $this->view->updateCommentCount($comment["day_id"]);
          $lastCom = $this->view->getLastInsertComment($comment["time"], $comment["session_id"]);
          $arr = ["result"  => true,
                  "com_id"  => $comment["com_id"],
                  "content" => $this->view->getOneCommentHTML($lastCom)];
        }
      } else {
        $arr = ["result" => false, "error" => "Please check inputs"];
      }
      $this->response($arr);
    }

    /**
     * Like a day
     */
    public function ajaxLikeADay() {
      $id = (int)$this->post['id'];
      if ($id > 0) {
        $ipaddress = trim(System::getTodayIPaddress());
        $this->view->updateLikeDay($id, $ipaddress);
      }
    }

    /**
     * Like a comment
     */
    public function ajaxLikeAComment() {
      $id = (int)$this->post['id'];
      if ($id > 0) {
        $ipaddress = trim(System::getTodayIPaddress());
        $this->view->updateLikeComment($id, $ipaddress);
      }
    }

    /**
     * Dislike a comment
     */
    public function ajaxDislikeAComment() {
      $id = (int)$this->post['id'];
      if ($id > 0) {
        $ipaddress = trim(System::getTodayIPaddress());
        $this->view->updateDislikeComment($id, $ipaddress);
      }
    }
  }