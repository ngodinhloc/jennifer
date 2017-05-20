<?php
namespace thedaysoflife;

use html\HTML;
use sys\System;
use com\Com;
use core\Model;

class View extends Model {

  /**
   * Get the html output of one comment
   * @param array $row
   * @return string
   */
  public function getOneCommentHTML($row) {
    $output = "";
    if (isset($row['id'])) {
      $rep_id   = (int)$row['reply_id'];
      $com_id   = (int)$row['id'];
      $rep_name = trim($row['reply_name']);
      if ($rep_name != "") {
        $rep_name = "@<b>" . $rep_name . '</b>: ';
      }
      if ($rep_id == 0) {
        $rep_id    = $com_id;
        $rep_class = "";
      }
      else {
        $rep_class = "comment-reply";
      }
      $href_id    = $rep_id . "-" . $com_id;
      $time       = Com::getTimeDiff($row['time']);
      $like_ip    = explode('|', $row['like_ip']);
      $dislike_ip = explode('|', $row['dislike_ip']);
      $ipaddress  = System::getTodayIPaddress();

      $html   = new HTML();
      $output = $html->setTag("div")->setClass("media comment {$rep_class}")->setID("comment-{$row['id']}")->open() .
                $html->setTag("div")->setClass("media-body")->open() .
                $html->setTag("div")->setClass("author")->open() .
                $html->setTag("i")->setClass("icon")->create() .
                $html->setTag("span")->setID("name-{$row['id']}")->setInnerHTML($row['username'])->create() .
                $html->setTag("div")->close() .
                $html->setClass("date")->open() .
                $html->setTag("i")->setClass("icon")->create() .
                $html->setTag("span")->setInnerHTML($time)->create() .
                $html->setTag("div")->close() .
                $html->setTag("p")->setInnerHTML($rep_name . stripslashes($row['content']))->create() .
                $html->setTag("div")->setClass("stat pull-right")->open();
      if (in_array($ipaddress, $like_ip)) {
        $output .= $html->setTag("span")->setClass("like liked")->setProp(["title" => "Liked"])->open() .
                   $html->setTag("i")->setClass("icon")->create() .
                   number_format($row['like']) .
                   $html->setTag("span")->close();
      }
      else {
        $output .= $html->setTag("span")->setClass("like")->setProp(["title" => "Like"])->open() .
                   $html->setTag("a")->setProp(["href"      => "javascript:void(0)", "data-id" => $row["id"],
                                                "data-like" => $row["like"]])->setClass("like-com")
                        ->setID("like-com-{$row['id']}-{$row['like']}")->open() .
                   $html->setTag("i")->setClass("icon")->create() .
                   number_format($row['like']) .
                   $html->setTag("a")->close() .
                   $html->setTag("span")->close();
      }
      if (in_array($ipaddress, $dislike_ip)) {
        $output .= $html->setTag("span")->setClass("underlike disliked")->setProp(["title" => "Disliked"])->open() .
                   $html->setTag("i")->setClass("icon")->create() .
                   number_format($row['dislike']) .
                   $html->setTag("span")->close();
      }
      else {
        $output .= $html->setTag("span")->setClass("underlike")->setProp(["title" => "Dislike"])->open() .
                   $html->setTag("a")->setProp(["href"         => "javascript:void(0)", "data-id" => $row["id"],
                                                "data-dislike" => $row["dislike"]])->setClass("dislike-com")->open() .
                   $html->setTag("i")->setClass("icon")->create() .
                   number_format($row['dislike']) .
                   $html->setTag("a")->close() .
                   $html->setTag("span")->close();

      }
      $output .= $html->setTag("span")->setClass("reply")->open() .
                 $html->setTag("a")->setClass("reply-display")->setID($href_id)
                      ->setProp(["href"        => "javascript:void(0)", "data-com-id" => $com_id,
                                 "data-rep-id" => $rep_id])->open() .
                 $html->setTag("i")->setClass("icon")->create() . "Reply" .
                 $html->setTag("a")->close() .
                 $html->setTag("span")->close() .
                 $html->setTag("div")->close() .
                 $html->setTag("div")->close() .
                 $html->setTag("div")->close();
    }

    return $output;
  }

  /**
   * Get info by tag
   * @param string $tag
   * @return array
   */
  public function getInfoByTag($tag) {
    $row = $this->db->table("tbl_info")->select(["title", "content"])->where(["tag" => $tag])
                    ->get(false, "file")->first();

    return $row;
  }

  /**
   * Update comment when like
   * @param int $id
   * @param string $ipaddress
   * @return bool
   */
  public function updateLikeComment($id, $ipaddress) {
    $result = $this->db->table("#tbl_comment c")->where(["id" => $id])
                       ->set(["like"    => "#(c.like + 1)",
                              "like_ip" => "#CONCAT(like_ip, '|', '{$ipaddress}')"])
                       ->update();

    return $result;
  }

  /**
   * Update comment when dislike
   * @param int $id
   * @param string $ipaddress
   * @return bool
   */
  public function updateDislikeComment($id, $ipaddress) {
    $result = $this->db->table("#tbl_comment c")->where(["id" => $id])
                       ->set(["dislike"    => "#(c.dislike + 1)",
                              "dislike_ip" => "#CONCAT(dislike_ip,'|', '$ipaddress')"])
                       ->update();

    return $result;
  }

  /**
   * Get html output of comments of one day
   * @param int $dayID id of day
   * @return string
   */
  public function getComments($dayID) {
    $result = $this->db->table("tbl_comment")->where(["day_id" => $dayID])->orderBy(["id" => "ASC"])->get()->toArray();
    $coms   = [];
    $replys = [];
    $output = '';
    if ($result && !empty($result)) {
      foreach ($result as $row) {
        if (isset($row['id'])) {
          $com_id = (int)$row['id'];
          $rep_id = (int)$row['reply_id'];
          if ($rep_id > 0) {
            $replys[$rep_id][$com_id] = $row;
          }
          else {
            $coms[$com_id] = $row;
          }
        }
      }

      foreach ($coms as $com_id => $com) {
        $output .= $this->getOneCommentHTML($com);
        $reps = $replys[$com_id];
        if (sizeof($reps) > 0) {
          foreach ($reps as $reply_id => $rep) {
            $output .= $this->getOneCommentHTML($rep);
          }
        }
      }
    }

    return $output;
  }

  /**
   * Add new comment
   * @param array $comment
   * @return bool
   */
  public function addComment($comment) {
    $result = $this->db->table("tbl_comment")
                       ->columns(["day_id", "content", "username", "email",
                                  "reply_id", "reply_name", "like",
                                  "date", "time", "ipaddress", "session_id"])
                       ->values([$comment["day_id"], $comment["content"], $comment["username"], $comment["email"],
                                 $comment["reply_id"], $comment["reply_name"], $comment["like"],
                                 $comment["date"], $comment["time"], $comment["ipaddress"], $comment["session_id"]])
                       ->insert();

    return $result;
  }

  /**
   * Get last inserted comment
   * @param int $time
   * @param string $session_id
   * @return array|null
   */
  public function getLastInsertComment($time, $session_id) {
    $row = $this->db->table("tbl_comment")->where(["time" => $time, "session_id" => $session_id])
                    ->orderBy(["id" => "DESC"])->limit(1)->get()->first();

    return $row;
  }

  /**
   * Get html output of the related days on right column
   * @param int $day
   * @param int $month
   * @param int $year
   * @param string $location
   * @return string
   */
  public function getRightRelatedDayHTML($day, $month, $year, $location) {
    $result = $this->db->table("tbl_day")->select(["id", "day", "year", "month", "slug", "title", "photos"])
                       ->where(["year" => $year, "month" => $month, "|location" => "~{$location}"])
                       ->orderBy(["day" => "ASC"])
                       ->limit(NUM_TOP_RIGHT)
                       ->get(false, "file")->toArray();

    $html   = new HTML();
    $output = "";
    if ($result) {
      foreach ($result as $row) {
        if (isset($row['id'])) {
          $link       = Com::getDayLink($row);
          $photos     = trim($row['photos']);
          $firstPhoto = "";
          if ($photos != "") {
            $photos     = explode(',', $photos);
            $photo      = $photos[0];
            $photoUrl   = Com::getPhotoURL($photo, PHOTO_THUMB_NAME);
            $firstPhoto = $html->setTag("img")->setProp(["src" => $photoUrl])->create();
          }
          $output .= $html->setTag("li")->setClass("right-list")->open() .
                     $html->setTag("div")->setClass("right-thumb")->open() .
                     $html->setTag("a")->setProp(["href" => $link])->setInnerHTML($firstPhoto)->create() .
                     $html->setTag("div")->close() .
                     $html->setTag("div")->setClass("right-title")->open() .
                     $html->setTag("a")->setProp(["href" => $link])->setInnerHTML(stripslashes($row['title']))
                          ->create() .
                     $html->setTag("div")->close() .
                     $html->setTag("div")->setClass("clear-both")->create() .
                     $html->setTag("li")->close();
        }
      }
    }

    return $output;
  }

  /**
   * Get the top number of days for the right column
   * @return string
   */
  public function getRightTopDayHTML() {
    $result = $this->db->table("tbl_day")->select(["id", "day", "year", "month", "slug", "title", "photos"])
                       ->orderBy(["like" => "DESC"])->limit(NUM_TOP_RIGHT)->get(false, "file")->toArray();

    $html   = new HTML();
    $output = "";
    if ($result) {
      foreach ($result as $row) {
        if (isset($row['id'])) {
          $link       = Com::getDayLink($row);
          $photos     = trim($row['photos']);
          $firstPhoto = "";
          if ($photos != "") {
            $photos     = explode(',', $photos);
            $photo      = $photos[0];
            $photoUrl   = Com::getPhotoURL($photo, PHOTO_THUMB_NAME);
            $firstPhoto = $html->setTag("img")->setProp(["src" => $photoUrl])->create();
          }
          $output .= $html->setTag("li")->setClass("right-list")->open() .
                     $html->setTag("div")->setClass("right-thumb")->open() .
                     $html->setTag("a")->setProp(["href" => $link])->setInnerHTML($firstPhoto)->create() .
                     $html->setTag("div")->close() .
                     $html->setTag("div")->setClass("right-title")->open() .
                     $html->setTag("a")->setProp(["href" => $link])->setInnerHTML(stripslashes($row['title']))
                          ->create() .
                     $html->setTag("div")->close() .
                     $html->setTag("div")->setClass("clear-both")->create() .
                     $html->setTag("li")->close();
        }
      }
    }

    return $output;
  }

  /**
   * Get the html output of calendar
   * @param int $from
   * @return string
   */
  public function getCalendar($from) {
    $result = $this->db->table("#tbl_day d")->select(["#DISTINCT d.year"])->orderBy(["year" => "DESC"])
                       ->offset($from)->limit(NUM_CALENDAR)->get()->toArray();

    $numRows = $this->db->count();
    $output = "";
    if ($numRows > 0) {
      $years = [];
      foreach ($result as $row) {
        array_push($years, $row['year']);
      }
      $yearsCond = implode(',', $years);
      $result1   = $this->db->table("#tbl_day d")
                            ->select(["day", "month", "year", "#CONCAT(d.day,d.month,d.year) as days",
                                      "#COUNT(*) as num"])
                            ->where(["year" => "@{$yearsCond}"])->groupBy(["#days"])
                            ->orderBy(["year" => "DESC", "month" => "ASC", "day" => "ASC"])
                            ->get()->toArray();
      $days      = [];
      foreach ($result1 as $row1) {
        $days[$row1['year']][$row1['month']][$row1['day']] = $row1['num'];
      }
      $months = ['Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06',
                 'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'];
      $search = SITE_URL . "/search/";

      $html   = new HTML();

      foreach ($years as $year) {
        $tag  = $year;
        $link = $search . $tag;
        $output .= $html->setTag("div")->setClass("calendar-div")->open() .
                   $html->setTag("h4")->open() .
                   $html->setTag("a")->setProp(["href" => $link])->setInnerHTML($year)->create() .
                   $html->setTag("h4")->close();

        foreach ($months as $abr => $month) {
          $day_num = $days[$year][$month];
          if (sizeof($day_num) > 0) {
            $output .= $html->setTag("ul")->setClass("list-unstyled calendar-year")->open();
            $tag  = $month . '/' . $year;
            $link = $search . $tag;
            $output .= $html->setTag("li")->setClass("calendar")->open() .
                       $html->setTag("a")->setProp(["href"  => $link,
                                                    "title" => "{$tag}: " . number_format(sizeof($day_num)) . " shares"])
                            ->open() .
                       $html->setTag("b")->setInnerHTML($abr)->create() .
                       $html->setTag("a")->close() .
                       $html->setTag("li")->close();
            foreach ($day_num as $day => $num) {
              if ($day > 0) {
                $tag  = $day . '/' . $month . '/' . $year;
                $link = $search . $tag;
              }
              else {
                $tag  = $month . '/' . $year;
                $link = $search . $tag;
              }
              $output .= $html->setTag("li")->setClass("calendar")->open() .
                         $html->setTag("a")->setProp(["href"  => $link,
                                                      "title" => "{$tag}: " . number_format($num) . " shares"])
                              ->setInnerHTML($day)->create() .
                         $html->setTag("li")->close();
            }
            $output .= $html->setTag("ul")->close() .
                       $html->setTag("br")->close();
          }
        }
        $output .= $html->setTag("div")->close();
      }
    }
    return $output;
  }

  /**
   * Get more days when scrolling on search page
   * @param string $search search term
   * @param int $from
   * @return string
   */
  public function getSearchMore($search, $from) {
    $search       = $this->escapeString($search);
    $matchedDay   = preg_match("/^\s*\d{1,2}\/\d{1,2}\/\d{2}(\d{2})?\s*$/", $search);
    $matchedMonth = preg_match("/^\s*\d{1,2}\/\d{2}(\d{2})?\s*$/", $search);
    $matchedYear  = is_numeric($search);

    $searchCond = [];
    if ($matchedDay) {
      $date  = explode('/', $search);
      $day   = $date[0];
      $month = $date[1];
      $year  = $date[2];
      $check = checkdate($month, $day, $year);
      if ($check) {
        $searchCond = ["year" => $year, "month" => $month, "day" => $day];
      }

    }
    else if ($matchedMonth) {
      $date       = explode('/', $search);
      $month      = $date[0];
      $year       = $date[1];
      $searchCond = ["year" => $year, "month" => $month];
    }
    else if ($matchedYear) {
      if (strlen($search) == 4) {
        $year = (int)$search;
      }
      $searchCond = ["year" => $year];
    }
    else {
      $searchCond = ["title"     => "~{$search}",
                     "|preview"  => "~{$search}",
                     "|username" => "~{$search}",
                     "|location" => "~{$search}",
                     "|sanitize" => "~{$search}"];
    }
    $orderCond = ["year" => "ASC", "month" => "ASC", "day" => "ASC", "like" => "DESC"];
    $result    = $this->db->table("tbl_day")->where($searchCond)->orderBy($orderCond)
                          ->offset($from)->limit(NUM_PER_PAGE)->get()->toArray();
    $output    = "";
    foreach ($result as $row) {
      $output .= $this->getOneDayHTML($row);
    }

    return $output;
  }

  /**
   * Get search result
   * @param string $search
   * @return string
   */
  public function getSearch($search) {
    $search       = $this->escapeString($search);
    $matchedDay   = preg_match("/^\s*\d{1,2}\/\d{1,2}\/\d{2}(\d{2})?\s*$/", $search);
    $matchedMonth = preg_match("/^\s*\d{1,2}\/\d{2}(\d{2})?\s*$/", $search);
    $matchedYear  = is_numeric($search);

    $searchCond = [];
    if ($matchedDay) {
      $date  = explode('/', $search);
      $day   = $date[0];
      $month = $date[1];
      $year  = $date[2];
      $check = checkdate($month, $day, $year);
      if ($check) {
        $searchCond = ["year" => $year, "month" => $month, "day" => $day];
      }
    }
    else if ($matchedMonth) {
      $date       = explode('/', $search);
      $month      = $date[0];
      $year       = $date[1];
      $searchCond = ["year" => $year, "month" => $month];
    }
    else if ($matchedYear) {
      if (strlen($search) == 4) {
        $year = (int)$search;
      }
      $searchCond = ["year" => $year];
    }
    else {
      $searchCond = ["title"     => "~{$search}",
                     "|preview"  => "~{$search}",
                     "|username" => "~{$search}",
                     "|location" => "~{$search}",
                     "|sanitize" => "~{$search}"];
    }

    $limit     = NUM_PER_PAGE * 2;
    $orderCond = ["year" => "ASC", "month" => "ASC", "day" => "ASC", "like" => "DESC"];
    $result    = $this->db->table("tbl_day")->where($searchCond)->orderBy($orderCond)->limit($limit)->get(true)->toArray();
    $total     = (int)$this->db->foundRows();

    $html   = new HTML();
    $output = "";
    $output .= $html->setTag("div")->setClass("search-found")->setInnerHTML("Found: {$total} Days")->create() .
               $html->setClass("row row-offcanvas row-offcanvas-right")->open() .
               $html->setTag("ul")->setID("slide-show")->setClass("list-unstyled")->open();

    if ($result && count($result) > 0) {
      foreach ($result as $row) {
        $output .= $this->getOneDayHTML($row);
      }
    }
    $output .= $html->close() .
               $html->setTag("div")->close();
    $current = $limit;
    if ($total > $current) {
      $output .= $html->setID("search-more")->setClass("show-more")->setProp(["data" => "search-{$current}"])
                      ->setInnerHTML("+ Show More Days")->create();

    }

    return $output;
  }

  /**
   * Get the picture of life
   * @param int $from
   * @return string
   */
  public function getPicture($from) {
    $result = $this->db->table("tbl_day")->select(["id", "title", "day", "month", "year", "slug", "photos"])
                       ->where(["photos"  => "#IS NOT NULL",
                                "#photos" => "!"])->orderBy(["id" => "DESC"])->offset($from)->limit(NUM_PICTURE)->get()
                       ->toArray();
    $html   = new HTML();
    $output = "";
    foreach ($result as $row) {
      $link      = Com::getDayLink($row);
      $title     = $row['day'] . '/' . $row['month'] . '/' . $row['year'] . ': ' . $row['title'];
      $photos    = trim($row['photos']);
      $fistPhoto = "";

      if ($photos != "") {
        $photos    = explode(',', $photos);
        $photo     = $photos[0];
        $photoURL  = Com::getPhotoURL($photo, PHOTO_THUMB_NAME);
        $fistPhoto = $html->setTag("a")->setProp(["href" => $link, "title" => $title])->open() .
                     $html->setTag("img")->setProp(["src" => $photoURL])->setClass("photo-thumb")->create() .
                     $html->setTag("a")->close();
      }

      $output .= $html->setTag("li")->setInnerHTML($fistPhoto)->create();
    }

    return $output;
  }

  /**
   * Get list of the days order by
   * @param int $from
   * @param string $order
   * @return string
   */
  public function getBestDays($from, $order) {
    if ($from == 0) {
      $limit = NUM_PER_PAGE * 2;
    }
    else {
      $limit = NUM_PER_PAGE;
    }
    $orderCond = null;
    if ($order == ORDER_BY_ID) {
      $orderCond = ["id" => "DESC"];
    }
    elseif ($order == ORDER_BY_LIKE) {
      $orderCond = ["like" => "DESC", "id" => "DESC"];
    }

    $days = $this->db->table("tbl_day")->select(["id", "day", "month", "year", "title", "slug", "preview", "username",
                                                 "location", "photos", "count", "like", "like_ip"])
                     ->orderBy($orderCond)->offset($from)->limit($limit)->get()->toArray();
    $html = '';
    foreach ($days as $day) {
      $html .= $this->getOneDayHTML($day);
    }

    return $html;
  }

  /**
   * Get html output of one day
   * @param array $row
   * @return string
   */
  protected function getOneDayHTML($row) {
    $link    = Com::getDayLink($row);
    $photos  = trim($row['photos']);
    $imgLink = "";
    $html    = new HTML();

    if ($photos != "") {
      $photos    = explode(',', $photos);
      $fistPhoto = $photos[0];
      $photoUrl  = Com::getPhotoURL($fistPhoto, PHOTO_TITLE_NAME);
      $image     = $html->setTag("img")->setProp(["src" => $photoUrl])->create();
      $imgLink   = $html->setTag("a")->setProp(["href" => $link])->setInnerHTML($image)->create();
    }
    $preview = trim(str_replace('<br>', ' ', $row['preview']));

    if (strlen($preview) > PREVIEW_LENGTH) {
      $preview  = Com::subString($preview, PREVIEW_LENGTH, 3);
      $moreLink = $html->setTag("a")->setProp(["href" => $link])->setInnerHTML("...more &raquo;")->create();
      $preview  = $preview . $moreLink;
    }
    $like_ip       = explode('|', $row['like_ip']);
    $ipaddress     = System::getTodayIPaddress();
    $search_author = SITE_URL . "/search/" . urlencode($row['username']);
    $search_loc    = SITE_URL . "/search/" . urlencode($row['location']);
    $authorLink    = $html->setTag("a")->setProp(["href" => $search_author])
                          ->setInnerHTML(stripslashes($row['username']))->create();
    $meta          = $authorLink;
    if ($row['location'] != '') {
      $locLink = $html->setTag("a")->setProp(["href" => $search_loc])
                      ->setInnerHTML('<i>' . stripslashes($row['location']) . '</i>')->create();
      $meta .= ' - ' . $locLink;
    }

    $output = $html->setTag("li")->setClass("item")->open() .
              $html->setTag("div")->setClass("images")->setInnerHTML($imgLink)->create() .
              $html->setTag("div")->setClass("body")->open() .
              $html->setTag("p")->open() .
              $html->setTag("a")->setProp(["href" => $link])
                   ->setInnerHTML($row['day'] . '/' . $row['month'] . '/' . $row['year'] . ': ' .
                                  stripslashes($row['title']))
                   ->create() .
              $html->setTag("p")->close() .
              $html->setTag("p")->setInnerHTML($preview)->create() .
              $html->setTag("p")->setClass("author-location")->open() .
              $html->setTag("span")->setInnerHTML($meta)->create() .
              $html->setTag("p")->close() .
              $html->setTag("div")->close() .
              $html->setTag("div")->setClass("stat")->open() .
              $html->setTag("span")->setClass("view")->open() .
              $html->setTag("a")->setProp(["href" => $link])->open() .
              $html->setTag("i")->setClass("icon")->create() . $row['count'] .
              $html->setTag("a")->close() .
              $html->setTag("span")->close();

    if (in_array($ipaddress, $like_ip)) {
      $output .= $html->setTag("span")->setClass("like liked")->setProp(["title" => "Liked"])->open() .
                 $html->setTag("i")->setClass("icon")->create() .
                 number_format($row['like']) .
                 $html->setTag("span")->close();
    }
    else {
      $output .= $html->setTag("span")->setClass("like")->setProp(["title" => "Like"])->open() .
                 $html->setTag("a")->setProp(["href"      => "javascript:void(0)", "data-id" => $row["id"],
                                              "data-like" => $row["like"]])->setClass("like-day")->open() .
                 $html->setTag("i")->setClass("icon")->create() .
                 number_format($row['like']) .
                 $html->setTag("a")->close() .
                 $html->setTag("span")->close();
    }
    $output .= $html->setTag("div")->close() .
               $html->setTag("li")->close();

    return $output;
  }

  /**
   * Insert new day
   * @param array $day
   * @return bool|\mysqli_result
   */
  public function addDay($day) {
    $code   = mt_rand(100000, 999999);
    $result = $this->db->table("tbl_day")->columns(["day", "month", "year", "title", "slug", "content", "preview",
                                                    "sanitize", "username", "email", "location", "edit_code", "notify",
                                                    "photos", "like", "date", "time", "ipaddress", "session_id"])
                       ->values([$day["day"], $day["month"], $day["year"], $day["title"], $day["slug"], $day["content"],
                                 $day["preview"], $day["sanitize"], $day["username"], $day["email"],
                                 $day["location"], $code, $day["notify"], $day["photos"], $day["like"], $day["date"],
                                 $day["time"], $day["ipaddress"], $day["session_id"]])
                       ->insert();

    return $result;
  }

  /**
   * Update comment count of a day
   * @param int $id
   * @return bool
   */
  public function updateCommentCount($id) {
    $result = $this->db->table("#tbl_day d")->where(["id" => $id])->set(["count" => "#(d.count + 1)"])->update();

    return $result;
  }

  /**
   * Update day when like
   * @param int $id
   * @param string $ipaddress
   * @return bool
   */
  public function updateLikeDay($id, $ipaddress) {
    $result = $this->db->table("#tbl_day d")->where(["id" => $id])
                       ->set(["like" => "#(d.like + 1)", "like_ip" => "#CONCAT(like_ip,'|','$ipaddress')"])
                       ->update();

    return $result;
  }

  /**
   * Get one day by id
   * @param int $id
   * @return array
   */
  public function getDayById($id) {
    $row = $this->db->table("tbl_day")->where(["id" => $id])->get()->first();

    return $row;
  }

  /**
   * Get the last insert day
   * @param int $time
   * @param string $session_id
   * @return array
   */
  public function getLastInsertDay($time, $session_id) {
    $row = $this->db->table("tbl_day")->where(["time" => $time, "session_id" => $session_id])
                    ->orderBy(["id" => "DESC"])->limit(1)->get()->first();

    return $row;
  }
}