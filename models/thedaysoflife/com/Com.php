<?php
namespace thedaysoflife\com;

use com\Common;
use html\HTML;

class Com extends Common {

  /**
   * @param array $day
   * @return string
   */
  public static function getDayLink($day) {
    return $link = LIST_URL . $day['id'] . '/' . $day['day'] . $day['month'] . $day['year'] . '-' . $day['slug'] .
                   URL_EXT;
  }

  /**
   * @param array $day
   * @return string
   */
  public static function getDayDescription($day) {
    return self::subString(strip_tags($day['content']), DESC_LENGTH, 3);
  }

  /**
   * @param array $day
   * @return string
   */
  public static function getDayTitle($day) {
    return $day['day'] . '/' . $day['month'] . '/' . $day['year'] . ': ' . $day['title'];
  }

  /**
   * @param string $menu
   * @return string
   */
  public function getDashboardMenu($menu) {
    $array  = ['home'    => '/back/home/',
               'days'    => '/back/days/',
               'about'   => '/back/about/',
               'privacy' => '/back/privacy/',
               'tools'   => '/back/tools/',];
    $html   = new HTML();
    $output = "";
    foreach ($array as $text => $page) {
      if ($menu == $text) {
        $output .= $html->setTag("li")->setClass("active")->open() .
                   $html->setTag("a")->setProp(["href" => $page])->setInnerHTML(ucfirst($text))->create() .
                   $html->setTag("li")->close();
      }
      else {
        $output .= $html->setTag("li")->open() .
                   $html->setTag("a")->setProp(["href" => $page])->setInnerHTML(ucfirst($text))->create() .
                   $html->setTag("li")->close();
      }
    }

    return $output;
  }

  /**
   * Get menu (HTML)
   * @param string $page
   * @return string
   */
  public static function getMenu($page) {
    $menuArray = ['index'    => ['title' => 'The Days Of Life', 'url' => SITE_URL],
                  'like'     => ['title' => 'Most Liked Days', 'url' => SITE_URL . '/like/'],
                  'calendar' => ['title' => 'The Calendar Of Life', 'url' => SITE_URL . '/calendar/'],
                  'picture'  => ['title' => 'The Picture Of Life', 'url' => SITE_URL . '/picture/'],
                  'about'    => ['title' => 'About', 'url' => SITE_URL . '/about/'],
                  'privacy'  => ['title' => 'Privacy', 'url' => SITE_URL . '/privacy/']];
    $html      = new HTML();
    $output    = "";
    foreach ($menuArray as $view => $menu) {
      if ($view == $page) {
        $output .= $html->setTag("li")->setClass("active")->open() .
                   $html->setTag("a")->setProp(["href" => $menu['url']])->setInnerHTML($menu['title'])->create() .
                   $html->setTag("li")->close();
      }
      else {
        $output .= $html->setTag("li")->open() .
                   $html->setTag("a")->setProp(["href" => $menu['url']])->setInnerHTML($menu['title'])->create() .
                   $html->setTag("li")->close();
      }
    }

    return $output;
  }

  /**
   * @param $name
   * @param $type
   * @return string
   */
  public static function getPhotoName($name, $type) {
    switch($type) {
      case PHOTO_FULL_NAME:
        $name = $name . PHOTO_FULL_NAME . PHOTO_EXT;
        break;
      case PHOTO_TITLE_NAME:
        $name = $name . PHOTO_TITLE_NAME . PHOTO_EXT;
        break;
      case PHOTO_THUMB_NAME:
        $name = $name . PHOTO_THUMB_NAME . PHOTO_EXT;
        break;
    }

    return $name;
  }

  /**
   * @param $name
   * @param $type
   * @return string
   */
  public static function getPhotoURL($name, $type) {
    $info  = explode('_', $name);
    $ym    = $info[0];
    $year  = substr($ym, 0, 4);
    $month = substr($ym, 4, 2);
    $name  = self::getPhotoName($name, $type);
    $url   = PHOTO_URL . $year . "/" . $month . "/" . $name;

    return $url;
  }

  /**
   * @param string $photos
   * @return array
   */
  public static function getPhotoPreviewArray($photos) {
    $photos = explode(',', $photos);
    $array  = [];
    foreach ($photos as $photo) {
      $thumb_url = self::getPhotoURL($photo, PHOTO_THUMB_NAME);
      $array []  = ["id" => $photo, "thumb" => $thumb_url];
    }

    return $array;
  }

  /**
   * @param array $photos
   * @param string $type PHOTO_FULL_NAME, PHOTO_THUMB_NAME
   * @return array
   */
  public static function getPhotoArray($photos, $type) {
    $array = [];
    foreach ($photos as $i => $name) {
      $array [] = self::getPhotoURL($name, $type);
    }

    return $array;
  }

  /**
   * @param $photos
   * @return string
   */
  public static function getPhotoSlideFull($photos) {
    $html   = new HTML();
    $output = "";
    foreach ($photos as $i => $name) {
      $url = self::getPhotoURL($name, PHOTO_FULL_NAME);
      $output .= $html->setTag("li")->open() .
                 $html->setTag("img")->setProp(["src" => $url])->create() .
                 $html->setTag("li")->close();
    }

    return $output;
  }

  /**
   * @param $photos
   * @return string
   */
  public static function getPhotoSlideThumb($photos) {
    $html   = new HTML();
    $output = "";
    foreach ($photos as $i => $name) {
      $url = self::getPhotoURL($name, PHOTO_THUMB_NAME);
      $output .= $html->setTag("li")->open() .
                 $html->setTag("img")->setProp(["src" => $url])->create() .
                 $html->setTag("span")->create() .
                 $html->setTag("li")->close();
    }

    return $output;
  }
}