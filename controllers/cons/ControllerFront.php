<?php
  namespace cons;

  use com\Common;
  use sys\System;
  use thedaysoflife\User;

  class ControllerFront extends Controller {
    private $user;

    public function __construct() {
      parent::__construct();

      $this->user = new User();
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
        $day["title"] = $this->user->escapeString($this->post['title']);
        $day["content"] = $this->user->escapeString($this->post['content']);
        $day["username"] = $this->user->escapeString($this->post['username']);
        $day["email"] = $this->user->escapeString($this->post['email']);
        $day["location"] = $this->user->escapeString($this->post['loc']);
        $day["photos"] = $this->user->escapeString($this->post['photos']);
        $day["slug"] = Common::sanitizeString($day["title"]);
        $day["preview"] = Common::subString($day["content"], SUMMARY_LENGTH, 3);
        $day["sanitize"] = str_replace('-', ' ', Common::sanitizeString($day["title"]))
                           . ' ' . str_replace('-', ' ', Common::sanitizeString($day["username"]))
                           . ' ' . str_replace('-', ' ', Common::sanitizeString($day["location"]))
                           . ' ' . str_replace('-', ' ', Common::sanitizeString($day["preview"]));
        $day["like"] = 0;
        $day["notify"] = "no";
        $day["time"] = time();
        $day["date"] = date('Y-m-d h:i:s');
        $day["ipaddress"] = System::getRealIPaddress();
        $day["session_id"] = System::sessionID();

        $re = $this->user->addDay($day);
        if ($re) {
          $row = $this->user->getLastInsertDay($day["time"], $day["session_id"]);
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
        $this->response($this->user->getDays($from, $order));
      }
    }

    /**
     * Search days
     */
    public function ajaxSearchDay() {
      $search = trim($this->post['search']);
      if ($search != "") {
        $this->response($this->user->getSearch($search));
      }
    }

    /**
     * Search more days (click on show more on search)
     */
    public function ajaxSearchMore() {
      $search = trim($this->post['search']);
      $from = (int)$this->post['from'];
      if ($search != "" && $from > 0) {
        $this->response($this->user->getSearchMore($search, $from));
      }
    }

    /**
     * Show calendar
     */
    public function ajaxShowCalendar() {
      $from = (int)$this->post['from'];
      if ($from > 0) {
        $this->response($this->user->getCalendar($from));
      }
    }

    /**
     * Show pictures
     */
    public function ajaxShowPicture() {
      $from = (int)$this->post['from'];
      if ($from > 0) {
        $this->response($this->user->getPicture($from));
      }
    }

    /**
     * Add new comment
     */
    public function ajaxMakeAComment() {
      $comment = ["day_id"     => (int)$this->post['day_id'],
                  "content"    => $this->user->escapeString($this->post['content']),
                  "username"   => $this->user->escapeString($this->post['username']),
                  "email"      => $this->user->escapeString($this->post['email']),
                  "reply_id"   => 0,
                  "reply_name" => '',
                  "like"       => 0,
                  "time"       => time(),
                  "date"       => date('Y-m-d h:i:s'),
                  "ipaddress"  => System::getRealIPaddress(),
                  "session_id" => System::sessionID()];
      $arr = [];
      if ($comment["day_id"] > 0 && $comment["content"] != "" && $comment["username"] != "" && $comment["email"] != "") {
        $re = $this->user->addComment($comment);
        if ($re) {
          $this->user->updateCommentCount($comment["day_id"]);
          $lastCom = $this->user->getLastInsertComment($comment["time"], $comment["session_id"]);
          $arr = ["result"  => true,
                  "day_id"  => $comment["day_id"],
                  "content" => $this->user->getOneCommentHTML($lastCom)];
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
                  "content"    => $this->user->escapeString($this->post['content']),
                  "username"   => $this->user->escapeString($this->post['username']),
                  "email"      => $this->user->escapeString($this->post['email']),
                  "reply_id"   => (int)$this->post['rep_id'],
                  "reply_name" => $this->user->escapeString($this->post['rep_name']),
                  "like"       => 0,
                  "time"       => time(),
                  "date"       => date('Y-m-d h:i:s'),
                  "ipaddress"  => System::getRealIPaddress(),
                  "session_id" => System::sessionID(),];
      $arr = [];

      if ($comment["day_id"] > 0 && $comment["content"] != "" && $comment["username"] != "" && $comment["email"] != "" &&
          $comment["reply_id"] > 0
      ) {
        $re = $this->user->addComment($comment);
        if ($re) {
          $this->user->updateCommentCount($comment["day_id"]);
          $lastCom = $this->user->getLastInsertComment($comment["time"], $comment["session_id"]);
          $arr = ["result"  => true,
                  "com_id"  => $comment["com_id"],
                  "content" => $this->user->getOneCommentHTML($lastCom)];
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
        $this->user->updateLikeDay($id, $ipaddress);
      }
    }

    /**
     * Like a comment
     */
    public function ajaxLikeAComment() {
      $id = (int)$this->post['id'];
      if ($id > 0) {
        $ipaddress = trim(System::getTodayIPaddress());
        $this->user->updateLikeComment($id, $ipaddress);
      }
    }

    /**
     * Dislike a comment
     */
    public function ajaxDislikeAComment() {
      $id = (int)$this->post['id'];
      if ($id > 0) {
        $ipaddress = trim(System::getTodayIPaddress());
        $this->user->updateDislikeComment($id, $ipaddress);
      }
    }
  }