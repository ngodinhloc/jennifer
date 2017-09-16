<?php
namespace html;

/**
 * Class Elements
 * @package html
 */
class Element {
  /**
   * Generate pagination
   * @param string $linkClass : class of href
   * @param int $pageNum
   * @param int $page
   * @param int $gap
   * @return string
   */
  public static function pagination($linkClass, $pageNum, $page, $gap = 4) {
    $html = new HTML();
    if ($pageNum > 0) {
      $start  = ($page - $gap) > 0 ? ($page - $gap) : 1;
      $end    = ($page + $gap) < $pageNum ? ($page + $gap) : $pageNum;
      $output = "";
      $output .= $html->setTag("div")->setClass("page-list")->open() .
                 $html->setTag("span")->setID("loader")->create() . " Page " . $page . "/" . $pageNum . " ";
      if ($page > 1) {
        $output .= $html->setTag("a")->setClass($linkClass)
                        ->setProp(["href"      => "javascript:void(0)",
                                   "data-page" => $page - 1,
                                   "title"     => "Previous Page"])->setInnerHTML("<")->create();
      }
      for ($i = $start; $i <= $end; $i++) {
        if ($i == $page) {
          $output .= $html->setTag("span")->setClass("current-page")->setInnerHTML($i)->create();
        }
        else {
          $output .= $html->setTag("a")->setClass($linkClass)
                          ->setProp(["href" => "javascript:void(0)", "data-page" => $i])->setInnerHTML($i)->create();
        }
      }
      if ($page < $pageNum) {
        $output .= $html->setTag("a")->setClass($linkClass)
                        ->setProp(["href"      => "javascript:void(0)",
                                   "data-page" => $page + 1,
                                   "title"     => "Next Page"])->setInnerHTML(">")->create();
      }
      $output .= $html->setTag("div")->close();

      return $output;
    }
  }

  /**
   * Generate options of select
   * @param array $arr
   * @param null $selected
   * @param null $class
   * @return string
   */
  public static function options($arr = [], $selected = null, $class = null) {
    $html   = new HTML();
    $output = "";
    foreach ($arr as $text => $value) {
      if ($selected == $value) {
        $output .= $html->setTag("option")->setClass("{$class}")->setProp(["value"    => $value,
                                                                           "selected" => "selected"])
                        ->setInnerHTML($text)->create();
      }
      else {
        $output .= $html->setTag("option")->setClass("{$class}")->setProp(["value" => $value])
                        ->setInnerHTML($text)->create();
      }
    }

    return $output;
  }

  /**
   * Generate radio buttons
   * @param array $arr
   * @param string $name
   * @param null $selected
   * @param null $class
   * @param string $separate
   * @return string
   */
  public static function radios($arr = [], $name = "", $selected = null, $class = null, $separate = "") {
    $html   = new HTML();
    $output = "";
    foreach ($arr as $value => $text) {
      if ($selected == $value) {
        $output .= $html->setTag("input")->setClass("{$class}")->setProp(["type"    => "radio",
                                                                          "name"    => $name,
                                                                          "value"   => $value,
                                                                          "checked" => "checked"])
                        ->create() . " " . $text . $separate;
      }
      else {
        $output .= $html->setTag("input")->setClass("{$class}")->setProp(["type"  => "radio",
                                                                          "name"  => $name,
                                                                          "value" => $value])
                        ->create() . " " . $text . $separate;
      }
    }

    return $output;
  }

  public static function div($name, $id, $class, $propertyList, $innerHTML) {
    $html = new HTML();
    $html->setTag("div")->setAttribute($id, $name, $class, $propertyList, $innerHTML);

    return $html->create();
  }

  public static function span($name, $id, $class, $propertyList, $innerHTML) {
    $html = new HTML();
    $html->setTag("span")->setAttribute($id, $name, $class, $propertyList, $innerHTML);

    return $html->create();
  }

  public static function ul($name, $id, $class, $propertyList, $innerHTML) {
    $html = new HTML();
    $html->setTag("ul")->setAttribute($id, $name, $class, $propertyList, $innerHTML);

    return $html->create();
  }

  public static function li($name, $id, $class, $propertyList, $innerHTML) {
    $html = new HTML();
    $html->setTag("li")->setAttribute($id, $name, $class, $propertyList, $innerHTML);

    return $html->create();
  }
}