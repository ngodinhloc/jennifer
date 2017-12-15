<?php
namespace jennifer\core;

use jennifer\db\Capsule;

/**
 * Base model class, all business classes should extend this core class
 * @see \thedaysoflife\model\
 * @package core
 */
class Model {
  /** @var Capsule */
  protected $db;

  public function __construct() {
    $this->db = new Capsule();
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
