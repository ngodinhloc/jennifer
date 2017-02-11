<?php
namespace core;
use com\Com;
use html\HTML;

class Admin extends Model {
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

    $count = 0;
    $size  = 0;
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
    print ($count . ' photos/' . number_format(($size / 1000000), 2) . ' MB');
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
    $title   = $this->escapeString($title);
    $content = $this->escapeString($content, true);
    $result  = $this->db->table("tbl_info")->where(["tag" => $tag])->set(["title"   => $title,
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
   * @param $id
   * @param $day
   * @param $month
   * @param $year
   * @param $title
   * @param $slug
   * @param $content
   * @param $preview
   * @param $sanitize
   * @param $photos
   * @param $username
   * @param $email
   * @param $location
   * @param $like
   * @return bool
   */
  public function updateDay($id, $day, $month, $year, $title, $slug, $content, $preview, $sanitize, $photos, $username,
                            $email, $location, $like) {
    $title    = $this->escapeString($title);
    $slug     = $this->escapeString($slug);
    $content  = $this->escapeString($content);
    $preview  = $this->escapeString($preview);
    $sanitize = $this->escapeString($sanitize);
    $photos   = $this->escapeString($photos);
    $username = $this->escapeString($username);
    $email    = $this->escapeString($email);
    $location = $this->escapeString($location);

    $result = $this->db->table("tbl_day")->where(["id" => $id])->set(["day"     => $day, "month" => $month,
                                                                      "year"    => $year, "title" => $title,
                                                                      "slug"    => $slug, "content" => $content,
                                                                      "preview" => $preview, "sanitize" => $sanitize,
                                                                      "photos"  => $photos, "username" => $username,
                                                                      "email"   => $email, "location" => $location,
                                                                      "like"    => $like])->update();

    return $result;
  }

  /**
   * @param $page
   * @return string
   */
  public function getDayList($page) {
    $limit   = NUM_PER_PAGE_ADMIN;
    $from    = $limit * ($page - 1);
    $result  = $this->db->table("tbl_day")->select(["id", "title", "day", "month", "year", "slug", "username", "count",
                                                    "like", "fb"])
                        ->orderBy(["id" => "DESC"])->offset($from)
                        ->limit($limit)->get(true)->toArray();
    $total   = $this->db->foundRows();
    $pageNum = ceil($total / $limit);
    $html    = new HTML();
    $output  = "<table>
				<thead>
				<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Author</th>
				<th>Com</th>
				<th>Like</th>
				<th colspan='2'>To Facebook</th>
				<th colspan='2'>Action</th>
				</tr>
				</thead>
				<tbody>";
    foreach ($result as $row) {
      $link = Com::getDayLink($row);
      $output .= '<tr id="row-' . $row['id'] . '">
					<td>' . $row['id'] . '</td>
					<td><a target="_blank" href="' . $link . '">' . $row['day'] . '/' . $row['month'] . '/' . $row['year'] .
                 ': ' . stripslashes($row['title']) . '</a></td>
					<td>' . stripslashes($row['username']) . '</td>
					<td>' . number_format($row['count']) . '</td>
					<td>' . number_format($row['like']) . '</td>
					<td>
						<select class="fb-type" id="fb-type-' . $row['id'] . '">';
      $output .= Com::getFBAct($row["fb"]);
      $output .= '</select>
					</td>
					<td id="fb-post-' . $row["id"] .
                 '"><a title="Post to Facebook" href="javascript:void(0)" class="fb-post-button" data-id="' .
                 $row['id'] . '"><span class="glyphicon glyphicon-send"></span></a></td>
					<td><a title="Edit" href="/back/edit/' . $row['id'] . '/"><span class="glyphicon glyphicon-edit"></span></a></td>
					<td><a title="Remove" href="javascript:void(0)" class="remove-day" id="remove-day-' . $row['id'] . '">
					<span class="glyphicon glyphicon-remove"></span></a></td>
					</tr>';
    }
    $output .= '</tbody></table>';
    $output .= Com::getPagination("", $pageNum, $page, 4);

    return $output;
  }
}