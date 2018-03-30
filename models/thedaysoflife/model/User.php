<?php

  namespace thedaysoflife\model;

  use jennifer\core\Model;
  use jennifer\html\HTML;
  use jennifer\sys\Globals;
  use jennifer\template\Template;
  use thedaysoflife\com\Com;
  use thedaysoflife\sys\Configs;

  class User extends Model {
    const ORDER_BY_ID   = 1;
    const ORDER_BY_LIKE = 2;

    /**
     * Get the html output of one comment
     * @param array $row
     * @return string
     */
    public function getOneCommentHTML($row) {
      $output = "";
      if (isset($row['id'])) {
        $replyID = (int)$row['reply_id'];
        $repName = trim($row['reply_name']) == "" ? "" : "@<b>" . trim($row['reply_name']) . "</b>: ";
        if ($replyID == 0) {
          $replyID = $row['id'];
          $repClass = "";
        } else {
          $repClass = "comment-reply";
        }
        $time = Com::getTimeDiff($row['time']);
        $likeIP = explode('|', $row['like_ip']);
        $dislikeIP = explode('|', $row['dislike_ip']);
        $ipaddress = Globals::todayIPAddress();

        $comment = ["id"       => $row["id"],
                    "username" => $row["username"],
                    "content"  => $row["content"],
                    "like"     => $row["like"],
                    "dislike"  => $row["dislike"],
                    "repID"    => $replyID,
                    "repName"  => $repName,
                    "repClass" => $repClass,
                    "time"     => $time,
                    "liked"    => in_array($ipaddress, $likeIP),
                    "disliked" => in_array($ipaddress, $dislikeIP),];
        $template = new Template("front/tpl/one_comment", ["comment" => $comment]);
        $output = $template->render();
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
      $result = $this->db->table("tbl_comment")->where(["day_id" => $dayID])->orderBy(["id" => "ASC"])->get()
                         ->toArray();
      $coms = [];
      $replys = [];
      $output = '';
      if ($result && !empty($result)) {
        foreach ($result as $row) {
          if (isset($row['id'])) {
            $comID = (int)$row['id'];
            $repID = (int)$row['reply_id'];
            if ($repID > 0) {
              $replys[$repID][$comID] = $row;
            } else {
              $coms[$comID] = $row;
            }
          }
        }

        foreach ($coms as $comID => $com) {
          $output .= $this->getOneCommentHTML($com);
          $reps = $replys[$comID];
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
      $comment["content"] = $this->escapeString($comment["content"]);
      $comment["username"] = $this->escapeString($comment["username"]);
      $comment["email"] = $this->escapeString($comment["email"]);
      $comment["reply_name"] = $this->escapeString($comment["reply_name"]);
      $result = $this->db->table("tbl_comment")
                         ->columns(["day_id",
                                    "content",
                                    "username",
                                    "email",
                                    "reply_id",
                                    "reply_name",
                                    "like",
                                    "date",
                                    "time",
                                    "ipaddress",
                                    "session_id"])
                         ->values([$comment["day_id"],
                                   $comment["content"],
                                   $comment["username"],
                                   $comment["email"],
                                   $comment["reply_id"],
                                   $comment["reply_name"],
                                   $comment["like"],
                                   $comment["date"],
                                   $comment["time"],
                                   $comment["ipaddress"],
                                   $comment["session_id"]])
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
     * @param array $options ["day" =>, "month" =>, "year" =>]
     * @param bool $htmlReturn
     * @return array|bool|string
     */
    public function getRightRelatedDays($options = [], $htmlReturn = true) {
      $result = $this->db->table("tbl_day")->select(["id", "day", "year", "month", "slug", "title", "photos"])
                         ->where(["year"      => $options["year"],
                                  "month"     => $options["month"],
                                  "|location" => "~{$options["location"]}"])
                         ->orderBy(["day" => "ASC"])
                         ->limit(Configs::NUM_TOP_RIGHT)
                         ->get(false, "file")->toArray();
      if ($htmlReturn) {
        $output = $this->getRightListHTML($result);

        return $output;
      }

      return $result;
    }

    /**
     * Get the top number of days for the right column
     * @param bool $htmlReturn
     * @return array|bool|string
     */
    public function getRightTopDays($htmlReturn = true) {
      $result = $this->db->table("tbl_day")->select(["id", "day", "year", "month", "slug", "title", "photos"])
                         ->orderBy(["like" => "DESC"])->limit(Configs::NUM_TOP_RIGHT)->get(false, "file")->toArray();
      if ($htmlReturn) {
        $output = $this->getRightListHTML($result);

        return $output;
      }

      return $result;
    }

    /**
     * @param array $days
     * @return string
     */
    protected function getRightListHTML($days = []) {
      $output = "";
      if ($days) {
        $rightList = [];
        foreach ($days as $row) {
          if (isset($row['id'])) {
            $rightList[] = ["link"     => Com::getDayLink($row),
                            "title"    => $row["title"],
                            "photoURL" => Com::getFirstPhotoURL($row, Configs::PHOTO_THUMB_NAME)];
          }
        }

        $template = new Template("front/tpl/right_list", ["days" => $rightList]);
        $output = $template->render();
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
                         ->offset($from)->limit(Configs::NUM_CALENDAR)->get()->toArray();

      $numRows = $this->db->count();
      $output = "";
      if ($numRows > 0) {
        $years = [];
        foreach ($result as $row) {
          array_push($years, $row['year']);
        }
        $yearsCond = implode(',', $years);
        $result1 = $this->db->table("#tbl_day d")
                            ->select(["day",
                                      "month",
                                      "year",
                                      "#CONCAT(d.day,d.month,d.year) as days",
                                      "#COUNT(*) as num"])
                            ->where(["year" => "@{$yearsCond}"])->groupBy(["#days"])
                            ->orderBy(["year" => "DESC", "month" => "ASC", "day" => "ASC"])
                            ->get()->toArray();
        $days = [];
        foreach ($result1 as $row1) {
          $days[$row1['year']][$row1['month']][$row1['day']] = $row1['num'];
        }
        $months = ['Jan' => '01',
                   'Feb' => '02',
                   'Mar' => '03',
                   'Apr' => '04',
                   'May' => '05',
                   'Jun' => '06',
                   'Jul' => '07',
                   'Aug' => '08',
                   'Sep' => '09',
                   'Oct' => '10',
                   'Nov' => '11',
                   'Dec' => '12'];

        $template = new Template("front/tpl/preview_calendar", ["years"  => $years,
                                                                "days"   => $days,
                                                                "months" => $months]);
        $output = $template->render();
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
      $search = $this->escapeString($search);
      $matchedDay = preg_match("/^\s*\d{1,2}\/\d{1,2}\/\d{2}(\d{2})?\s*$/", $search);
      $matchedMonth = preg_match("/^\s*\d{1,2}\/\d{2}(\d{2})?\s*$/", $search);
      $matchedYear = is_numeric($search);

      $searchCond = [];
      if ($matchedDay) {
        $date = explode('/', $search);
        $day = $date[0];
        $month = $date[1];
        $year = $date[2];
        $check = checkdate($month, $day, $year);
        if ($check) {
          $searchCond = ["year" => $year, "month" => $month, "day" => $day];
        }

      } else if ($matchedMonth) {
        $date = explode('/', $search);
        $month = $date[0];
        $year = $date[1];
        $searchCond = ["year" => $year, "month" => $month];
      } else if ($matchedYear) {
        if (strlen($search) == 4) {
          $year = (int)$search;
        }
        $searchCond = ["year" => $year];
      } else {
        $searchCond = ["title"     => "~{$search}",
                       "|preview"  => "~{$search}",
                       "|username" => "~{$search}",
                       "|location" => "~{$search}",
                       "|sanitize" => "~{$search}"];
      }
      $orderCond = ["year" => "ASC", "month" => "ASC", "day" => "ASC", "like" => "DESC"];
      $result = $this->db->table("tbl_day")->where($searchCond)->orderBy($orderCond)
                         ->offset($from)->limit(Configs::NUM_PER_PAGE)->get()->toArray();
      $previewDays = $this->getPreviewDays($result);

      return $previewDays;
    }

    /**
     * Get search result
     * @param string $search
     * @return string
     */
    public function getSearch($search) {
      $search = $this->escapeString($search);
      $matchedDay = preg_match("/^\s*\d{1,2}\/\d{1,2}\/\d{2}(\d{2})?\s*$/", $search);
      $matchedMonth = preg_match("/^\s*\d{1,2}\/\d{2}(\d{2})?\s*$/", $search);
      $matchedYear = is_numeric($search);

      $searchCond = [];
      if ($matchedDay) {
        $date = explode('/', $search);
        $day = $date[0];
        $month = $date[1];
        $year = $date[2];
        $check = checkdate($month, $day, $year);
        if ($check) {
          $searchCond = ["year" => $year, "month" => $month, "day" => $day];
        }
      } else if ($matchedMonth) {
        $date = explode('/', $search);
        $month = $date[0];
        $year = $date[1];
        $searchCond = ["year" => $year, "month" => $month];
      } else if ($matchedYear) {
        if (strlen($search) == 4) {
          $year = (int)$search;
        }
        $searchCond = ["year" => $year];
      } else {
        $searchCond = ["title"     => "~{$search}",
                       "|preview"  => "~{$search}",
                       "|username" => "~{$search}",
                       "|location" => "~{$search}",
                       "|sanitize" => "~{$search}"];
      }

      $limit = Configs::NUM_PER_PAGE * 2;
      $orderCond = ["year" => "ASC", "month" => "ASC", "day" => "ASC", "like" => "DESC"];
      $result = $this->db->table("tbl_day")->where($searchCond)->orderBy($orderCond)->limit($limit)->get(true)
                         ->toArray();
      $total = (int)$this->db->foundRows();

      $html = new HTML();
      $output = "";
      $output .= $html->setTag("div")->setClass("search-found")->setInnerHTML("Found: {$total} Days")->create() .
                 $html->setClass("row row-offcanvas row-offcanvas-right")->open() .
                 $html->setTag("ul")->setID("slide-show")->setClass("list-unstyled")->open();

      if ($result && count($result) > 0) {
        $previewDays = $this->getPreviewDays($result);
        $output .= $previewDays;
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
                         ->where(["photos" => "#IS NOT NULL", "#photos" => "!"])
                         ->orderBy(["id" => "DESC"])->offset($from)->limit(Configs::NUM_PICTURE)
                         ->get()->toArray();
      $photos = [];
      foreach ($result as $row) {
        $photos[] = ["link"     => Com::getDayLink($row),
                     "title"    => Com::getDayTitle($row),
                     "photoURL" => Com::getFirstPhotoURL($row, Configs::PHOTO_THUMB_NAME)];
      }

      $template = new Template("front/tpl/preview_photos", ["photos" => $photos]);

      return $template->render();
    }

    /**
     * Get list of the days order by
     * @param int $from
     * @param string $order
     * @return string
     */
    public function getDays($from, $order) {
      $limit = ($from == 0) ? (Configs::NUM_PER_PAGE * 2) : Configs::NUM_PER_PAGE;
      $orderCond = null;
      switch ($order) {
        case self::ORDER_BY_ID:
          $orderCond = ["id" => "DESC"];
          break;
        case self::ORDER_BY_LIKE:
          $orderCond = ["like" => "DESC", "id" => "DESC"];
          break;
      }

      $days = $this->db->table("tbl_day")->select(["id",
                                                   "day",
                                                   "month",
                                                   "year",
                                                   "title",
                                                   "slug",
                                                   "preview",
                                                   "username",
                                                   "location",
                                                   "photos",
                                                   "count",
                                                   "like",
                                                   "like_ip"])
                       ->orderBy($orderCond)->offset($from)->limit($limit)->get()->toArray();
      $html = $this->getPreviewDays($days);

      return $html;
    }

    /**
     * @param array $days
     * @return string
     */
    protected function getPreviewDays($days = []) {
      $previewDays = [];
      foreach ($days as $row) {
        $ipAddress = Globals::todayIPAddress();
        $likeIP = explode('|', $row['like_ip']);
        $liked = in_array($ipAddress, $likeIP);

        $previewDays [] = ["title"        => $row["title"],
                           "like"         => $row["like"],
                           "count"        => $row["count"],
                           "author"       => $row["username"],
                           "location"     => $row["location"],
                           "link"         => Com::getDayLink($row),
                           "photoURL"     => Com::getFirstPhotoURL($row),
                           "preview"      => Com::getDayPreviewText($row),
                           "authorLink"   => Com::getSearchLink($row['username']),
                           "locationLink" => $row['location'] != '' ? Com::getSearchLink($row['location']) : false,
                           "liked"        => $liked,];
      }

      $template = new Template("front/tpl/preview_days", ["days" => $previewDays]);

      return $template->render();
    }

    /**
     * Insert new day
     * @param array $day
     * @return bool|\mysqli_result
     */
    public function addDay($day) {
      $day["title"] = $this->escapeString($day["title"]);
      $day["content"] = $this->escapeString($day["content"]);
      $day["username"] = $this->escapeString($day["username"]);
      $day["email"] = $this->escapeString($day["email"]);
      $day["location"] = $this->escapeString($day["location"]);
      $day["photos"] = $this->escapeString($day["photos"]);
      $code = mt_rand(100000, 999999);
      $result = $this->db->table("tbl_day")->columns(["day",
                                                      "month",
                                                      "year",
                                                      "title",
                                                      "slug",
                                                      "content",
                                                      "preview",
                                                      "sanitize",
                                                      "username",
                                                      "email",
                                                      "location",
                                                      "edit_code",
                                                      "notify",
                                                      "photos",
                                                      "like",
                                                      "date",
                                                      "time",
                                                      "ipaddress",
                                                      "session_id"])
                         ->values([$day["day"],
                                   $day["month"],
                                   $day["year"],
                                   $day["title"],
                                   $day["slug"],
                                   $day["content"],
                                   $day["preview"],
                                   $day["sanitize"],
                                   $day["username"],
                                   $day["email"],
                                   $day["location"],
                                   $code,
                                   $day["notify"],
                                   $day["photos"],
                                   $day["like"],
                                   $day["date"],
                                   $day["time"],
                                   $day["ipaddress"],
                                   $day["session_id"]])
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