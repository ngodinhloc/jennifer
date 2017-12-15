<?php
namespace cons;

use jennifer\com\Common;
use jennifer\controller\Controller;
use jennifer\sys\Globals;
use thedaysoflife\model\User;

class ControllerFront extends Controller {
  /** @var User */
  private $user;

  public function __construct() {
    parent::__construct();
    $this->user = new User();
  }

  /**
   * User made new day
   * @return array
   */
  public function ajaxMakeADay() {
    $day          = [];
    $day["day"]   = (int)$this->post['day'];
    $day["month"] = (int)$this->post['month'];
    $day["year"]  = (int)$this->post['year'];
    $check        = checkdate($day["month"], $day["day"], $day["year"]);
    if ($check) {
      $day["title"]      = $this->user->escapeString($this->post['title']);
      $day["content"]    = $this->user->escapeString($this->post['content']);
      $day["username"]   = $this->user->escapeString($this->post['username']);
      $day["email"]      = $this->user->escapeString($this->post['email']);
      $day["location"]   = $this->user->escapeString($this->post['loc']);
      $day["photos"]     = $this->user->escapeString($this->post['photos']);
      $day["slug"]       = Common::sanitizeString($day["title"]);
      $day["preview"]    = Common::subString($day["content"], SUMMARY_LENGTH, 3);
      $day["sanitize"]   = str_replace('-', ' ', Common::sanitizeString($day["title"]))
                           . ' ' . str_replace('-', ' ', Common::sanitizeString($day["username"]))
                           . ' ' . str_replace('-', ' ', Common::sanitizeString($day["location"]))
                           . ' ' . str_replace('-', ' ', Common::sanitizeString($day["preview"]));
      $day["like"]       = 0;
      $day["notify"]     = "no";
      $day["time"]       = time();
      $day["date"]       = date('Y-m-d h:i:s');
      $day["ipaddress"]  = Globals::realIPAddress();
      $day["session_id"] = Globals::sessionID();

      $re = $this->user->addDay($day);
      if ($re) {
        $row          = $this->user->getLastInsertDay($day["time"], $day["session_id"]);
        $this->result = ["status" => "success",
                         "id"     => $row['id'],
                         "slug"   => $row['slug'],
                         "day"    => $row['day'],
                         "month"  => $row['month'],
                         "year"   => $row['year']];

      }
    }

    return $this->result;
  }

  /**
   * Show list of days
   * @return string
   */
  public function ajaxShowDay() {
    $from  = (int)$this->post['from'];
    $order = $this->post['order'];
    if ($from > 0 && in_array($order, [User::ORDER_BY_ID, User::ORDER_BY_LIKE])) {
      $this->result = $this->user->getDays($from, $order);
    }

    return $this->result;
  }

  /**
   * Search day
   * @return string
   */
  public function ajaxSearchDay() {
    $search = $this->user->escapeString($this->post['search']);
    if ($search != "") {
      $this->result = $this->user->getSearch($search);
    }

    return $this->result;
  }

  /**
   * Get more search result
   * @return string
   */
  public function ajaxSearchMore() {
    $search = $this->user->escapeString($this->post['search']);
    $from   = (int)$this->post['from'];
    if ($search != "" && $from > 0) {
      $this->result = $this->user->getSearchMore($search, $from);
    }

    return $this->result;
  }

  /**
   * show calendar
   * @return string
   */
  public function ajaxShowCalendar() {
    $from = (int)$this->post['from'];
    if ($from > 0) {
      $this->result = $this->user->getCalendar($from);
    }

    return $this->result;
  }

  /**
   * Show picture
   * @return string
   */
  public function ajaxShowPicture() {
    $from = (int)$this->post['from'];
    if ($from > 0) {
      $this->result = $this->user->getPicture($from);
    }

    return $this->result;
  }

  /**
   * User add comment
   * @return array
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
                "ipaddress"  => Globals::realIPAddress(),
                "session_id" => Globals::sessionID()];
    if ($comment["day_id"] > 0 && $comment["content"] != "" && $comment["username"] != "" && $comment["email"] != "") {
      $re = $this->user->addComment($comment);
      if ($re) {
        $this->user->updateCommentCount($comment["day_id"]);
        $lastCom      = $this->user->getLastInsertComment($comment["time"], $comment["session_id"]);
        $this->result = ["result"  => true,
                         "day_id"  => $comment["day_id"],
                         "content" => $this->user->getOneCommentHTML($lastCom)];
      }
    }
    else {
      $this->result = ["result" => false, "error" => "Please check inputs"];
    }

    return $this->result;
  }

  /**
   * User reply to a comment
   * @return array
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
                "ipaddress"  => Globals::realIPAddress(),
                "session_id" => Globals::sessionID(),];
    if ($comment["day_id"] > 0 && $comment["content"] != "" && $comment["username"] != "" && $comment["email"] != "" &&
        $comment["reply_id"] > 0
    ) {
      $re = $this->user->addComment($comment);
      if ($re) {
        $this->user->updateCommentCount($comment["day_id"]);
        $lastCom      = $this->user->getLastInsertComment($comment["time"], $comment["session_id"]);
        $this->result = ["result"  => true,
                         "com_id"  => $comment["com_id"],
                         "content" => $this->user->getOneCommentHTML($lastCom)];
      }
    }
    else {
      $this->result = ["result" => false, "error" => "Please check inputs"];
    }

    return $this->result;
  }

  /**
   * User like a day
   * @return bool
   */
  public function ajaxLikeADay() {
    $id = (int)$this->post['id'];
    if ($id > 0) {
      $ipaddress    = trim(Globals::todayIPAddress());
      $this->result = $this->user->updateLikeDay($id, $ipaddress);
    }

    return $this->result;
  }

  /**
   * User like a comment
   * @return bool
   */
  public function ajaxLikeAComment() {
    $id = (int)$this->post['id'];
    if ($id > 0) {
      $ipaddress    = trim(Globals::todayIPAddress());
      $this->result = $this->user->updateLikeComment($id, $ipaddress);
    }

    return $this->result;
  }

  /**
   * User dislike a comment
   * @return bool
   */
  public function ajaxDislikeAComment() {
    $id = (int)$this->post['id'];
    if ($id > 0) {
      $ipaddress    = trim(Globals::todayIPAddress());
      $this->result = $this->user->updateDislikeComment($id, $ipaddress);
    }

    return $this->result;
  }
}