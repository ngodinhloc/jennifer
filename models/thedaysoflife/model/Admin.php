<?php
namespace thedaysoflife\model;

use core\Model;
use db\table\Day;
use template\Template;
use thedaysoflife\com\Com;

class Admin extends Model {
  public function testJoin() {
    $day = $this->db->table("tbl_day")->rightJoin("tbl_comment", "id", "day_id")->where(["#tbl_day.id" => 100099])
                    ->get()->toArray();
    //                  var_dump($day);
  }

  public function testTable() {
    $day   = new Day();
    $first = $day->where(["id" => ">0", "title" => "!"])->where(["year" => ">0"])->groupBy(["year"])
                 ->groupBy(["month", "day"])->orderBy(["year" => "ASC"])->orderBy(["month" => "DESC", "day" => "ASC"])
                 ->get();
    //      var_dump($day->count());
  }

  /**
   * @param $act
   * @return string
   */
  public function checkDatabaseTables($act) {
    return $this->db->checkDB($act);
  }

  /**
   * @param $year
   * @param $month
   * @return array
   */
  public function getPhotoByYearMonth($year, $month) {
    $result = $this->db->table("#tbl_day d")->select(["photos"])->where(["#YEAR(d.date)"  => $year,
                                                                         "#MONTH(d.date)" => $month,
                                                                         "#d.photos"      => "!"])->get()->toArray();
    $array  = [];
    foreach ($result as $row) {
      $photos = explode(',', $row['photos']);
      foreach ($photos as $i => $name) {
        $photo_full  = Com::getPhotoName($name, PHOTO_FULL_NAME);
        $photo_title = Com::getPhotoName($name, PHOTO_TITLE_NAME);
        $photo_thumb = Com::getPhotoName($name, PHOTO_THUMB_NAME);
        array_push($array, $photo_full);
        array_push($array, $photo_title);
        array_push($array, $photo_thumb);
      }

    }

    return $array;
  }

  /**
   * Remove unused photos
   */
  public function removeUnusedPhotos() {
    $result = $this->db->table("#tbl_day d")->select(["#DISTINCT YEAR(d.date) AS dyear", "#MONTH(d.date) AS dmonth"])
                       ->get()->toArray();
    $count  = 0;
    $size   = 0;
    foreach ($result as $row) {
      $year   = (int)$row['dyear'];
      $month  = (int)$row['dmonth'];
      $photos = $this->getPhotoByYearMonth($year, $month);
      $folder = DOC_ROOT . '/uploads/photos/' . $year . '/' . str_pad($month, 2, '0', STR_PAD_LEFT);
      $files  = array_filter(glob($folder . '/*'), 'is_file');

      foreach ($files as $file) {
        $name = end(explode('/', $file));
        if (!in_array($name, $photos)) {
          $size += filesize($file);
          unlink($file);
          $count++;
        }
      }
    }

    return ($count . ' photos/' . number_format(($size / 1000000), 2) . ' MB');
  }

  /**
   * Get info by tag
   * @param string $tag
   * @return array
   */
  public function getInfoByTag($tag) {
    $row = $this->db->table("tbl_info")->where(["tag" => $tag])->get()->first();

    return $row;
  }

  /**
   * Update info
   * @param string $tag
   * @param string $title
   * @param string $content
   * @return bool
   */
  public function updateInfo($tag, $title, $content) {
    $result = $this->db->table("tbl_info")->where(["tag" => $tag])->set(["title"   => $title,
                                                                         "content" => $content])->update();

    return $result;
  }

  /**
   * Check admin login
   * @param string $email
   * @param string $password
   * @return array
   */
  public function checkLogin($email, $password) {
    $row = $this->db->table("tbl_admin")->where(["email"    => $email,
                                                 "password" => $password])->limit(1)->get()->first();

    return $row;
  }

  /**
   * @param $id
   * @return mixed
   */
  public function removeDay($id) {
    $result = $this->db->table("tbl_day")->where(["id" => $id])->delete();

    return $result;
  }

  /**
   * @param $id
   * @return mixed
   */
  public function getDayById($id) {
    $row = $this->db->table("tbl_day")->where(["id" => $id])->get()->first();

    return $row;
  }

  /**
   * Update fb act
   * @param $id
   * @param $fb
   * @return bool
   */
  public function updateFB($id, $fb) {
    $result = $this->db->table("tbl_day")->where(["id" => $id])->set(["fb" => $fb])->update();

    return $result;
  }

  /**
   * Update day
   * @param array $day
   * @return bool
   */
  public function updateDay($day) {
    $result = $this->db->table("tbl_day")->where(["id" => $day["id"]])
                       ->set(["day"      => $day["day"],
                              "month"    => $day["month"],
                              "year"     => $day["year"],
                              "title"    => $day["title"],
                              "slug"     => $day["slug"],
                              "content"  => $day["content"],
                              "preview"  => $day["preview"],
                              "sanitize" => $day["sanitize"],
                              "photos"   => $day["photos"],
                              "username" => $day["username"],
                              "email"    => $day["email"],
                              "location" => $day["location"],
                              "like"     => $day["like"]])->update();

    return $result;
  }

  /**
   * @param $page
   * @return string
   */
  public function getDayList($page) {
    $limit   = NUM_PER_PAGE_ADMIN;
    $from    = $limit * ($page - 1);
    $result  = $this->db->table("tbl_day")->select(["id",
                                                    "title",
                                                    "day",
                                                    "month",
                                                    "year",
                                                    "slug",
                                                    "username",
                                                    "count",
                                                    "like",
                                                    "fb"])
                        ->orderBy(["id" => "DESC"])->offset($from)
                        ->limit($limit)->get(true)->toArray();
    $total   = $this->db->foundRows();
    $pageNum = ceil($total / $limit);
    $tpl     = new Template("back/tpl/list_days", ["days"       => $result,
                                                   "pagination" => Com::getPagination("page-nav", $pageNum, $page, 4)]);

    return $tpl->render();
  }
}