<?php
/**
 * Base model class: this should be the only class that initiate DB
 */
namespace core;

use db\DB;

class Model {
  protected $db;

  public function __construct() {
    $this->db = new DB();
  }

  public function __destruct() {
    unset($this->db);
  }

  /**
   * @param string $str
   * @param bool $admin
   * @return mixed|string
   */
  public function escapeString($str, $admin = false) {
    $str       = str_replace("&nbsp;", " ", $str);
    $find      = ["<p> </p>", "<p></p>"];
    $str       = str_replace($find, "", $str);
    $find      = ["\\n", "<br/>", "<br />"];
    $str       = str_replace($find, "<br>", $str);
    $str       = str_replace("'", '"', $str);
    $str       = trim(stripcslashes($str));
    $allowTags = "<br>";
    if ($admin) {
      $allowTags = "<p><br><b><i><ol><ul><li><strong>";
    }
    $str = strip_tags($str, $allowTags);
    $str = $this->db->escapeString($str);

    return $str;
  }
}
