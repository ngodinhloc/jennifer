<?php
  /**
   * Common static class
   */
  namespace com;

  use html\HTML;

  class Common {

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
     * @param string $linkClass : class of href
     * @param int $pageNum
     * @param int $page
     * @param int $gap
     * @return string
     */
    public static function getPagination($linkClass, $pageNum, $page, $gap = 4) {
      if ($pageNum > 0) {
        $start = ($page - $gap) > 0 ? ($page - $gap) : 1;
        $end = ($page + $gap) < $pageNum ? ($page + $gap) : $pageNum;
        $html = new HTML();
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
          } else {
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
     * Get FB options
     * @return string
     */
    public static function getFBAct($select) {
      $array = ["None" => "", "Album" => FB_ALBUM, "Link" => FB_LINK, "Feed" => FB_FEED, "Text" => FB_TEXT];
      $options = self::arrayToOptions($array, $select);

      return $options;
    }

    /**
     * @param array $arr
     * @param string $name
     * @param null $selected
     * @param null $class
     * @param string $separate
     * @return string
     */
    public static function arrayToRadios($arr = [], $name = "", $selected = null, $class = null, $separate = "") {
      $html = new HTML();
      $output = "";
      foreach ($arr as $value => $text) {
        if ($selected == $value) {
          $output .= $html->setTag("input")->setClass("{$class}")->setProp(["type"    => "radio",
                                                                            "name"    => $name,
                                                                            "value"   => $value,
                                                                            "checked" => "checked"])
                          ->create() . " " . $text . $separate;
        } else {
          $output .= $html->setTag("input")->setClass("{$class}")->setProp(["type"  => "radio",
                                                                            "name"  => $name,
                                                                            "value" => $value])
                          ->create() . " " . $text . $separate;
        }
      }

      return $output;
    }

    /**
     * @param array $arr
     * @param null $selected
     * @param null $class
     * @return string
     */
    public static function arrayToOptions($arr = [], $selected = null, $class = null) {
      $html = new HTML();
      $output = "";
      foreach ($arr as $text => $value) {
        if ($selected == $value) {
          $output .= $html->setTag("option")->setClass("{$class}")->setProp(["value"    => $value,
                                                                             "selected" => "selected"])
                          ->setInnerHTML($text)->create();
        } else {
          $output .= $html->setTag("option")->setClass("{$class}")->setProp(["value" => $value])
                          ->setInnerHTML($text)->create();
        }
      }

      return $output;
    }

    /**
     * Cast array to para string used in GET
     * @param $array
     * @return string
     */
    public static function arrayToParas($array) {
      if (count($array) == 0) {
        return "";
      }
      $str = "?";
      foreach ($array as $para => $value) {
        $str .= "{$para}={$value}&";
      }
      $str = substr($str, 0, strlen($str) - 1);

      return $str;
    }

    /**
     * @param $day
     * @return string
     */
    public static function getDayDrop($day = 0) {
      $range = range(1, 31);
      $arr = [];
      foreach ($range as $val) {
        $arr[$val] = $val;
      }
      $options = self::arrayToOptions($arr, $day, 'select');

      return $options;
    }

    /**
     * @param $month
     * @return string
     */
    public static function getMonthDrop($month = 0) {
      $range = range(1, 12);
      $arr = [];
      foreach ($range as $val) {
        $arr[$val] = $val;
      }
      $options = self::arrayToOptions($arr, $month, 'select');

      return $options;
    }

    /**
     * @param $year
     * @return string
     */
    public static function getYearDrop($year = 0) {
      $range = range(date('Y'), date('Y') - 100);
      $arr = [];
      foreach ($range as $val) {
        $arr[$val] = $val;
      }
      $options = self::arrayToOptions($arr, $year, 'select');

      return $options;
    }

    /**
     * @param string $menu
     * @return string
     */
    public function getDashboardMenu($menu) {
      $array = ['home'    => '/back/home/',
                'days'    => '/back/days/',
                'about'   => '/back/about/',
                'privacy' => '/back/privacy/',
                'tools'   => '/back/tools/',];
      $html = new HTML();
      $output = "";
      foreach ($array as $text => $page) {
        if ($menu == $text) {
          $output .= $html->setTag("li")->setClass("active")->open() .
                     $html->setTag("a")->setProp(["href" => $page])->setInnerHTML(ucfirst($text))->create() .
                     $html->setTag("li")->close();
        } else {
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
      $html = new HTML();
      $output = "";
      foreach ($menuArray as $view => $menu) {
        if ($view == $page) {
          $output .= $html->setTag("li")->setClass("active")->open() .
                     $html->setTag("a")->setProp(["href" => $menu['url']])->setInnerHTML($menu['title'])->create() .
                     $html->setTag("li")->close();
        } else {
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
      switch ($type) {
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
      $info = explode('_', $name);
      $ym = $info[0];
      $year = substr($ym, 0, 4);
      $month = substr($ym, 4, 2);
      $name = self::getPhotoName($name, $type);
      $url = PHOTO_URL . $year . "/" . $month . "/" . $name;

      return $url;
    }

    /**
     * @param string $photos
     * @return array
     */
    public static function getPhotoPreviewArray($photos) {
      $photos = explode(',', $photos);
      $array = [];
      foreach ($photos as $photo) {
        $thumb_url = self::getPhotoURL($photo, PHOTO_THUMB_NAME);
        $array [] = ["id" => $photo, "thumb" => $thumb_url];
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
      $html = new HTML();
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
      $html = new HTML();
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

    /**
     * @param $str
     * @return bool|mixed
     */
    public static function sanitizeString($str) {
      if (!$str) {
        return false;
      }
      $unicode = [
        'a'  => 'A|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ|á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ|À|Á|Â|Ã|Ä|Å|à|á|â|ã|ä|å|Ā|ā|Ă|ă|Ą|ą|Ǎ|ǎ|Ǻ|ǻ',
        'ae' => 'Æ|æ|Ǽ|ǽ',
        'b'  => 'B',
        'c'  => 'C|Ç|ç|Ć|ć|Ĉ|ĉ|Ċ|ċ|Č|č',
        'd'  => 'D|Đ|đ|Ď|ď|Đ|đ',
        'e'  => 'E|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|È|É|Ê|Ë|è|é|ê|ë|Ē|ē|Ĕ|ĕ|Ė|ė|Ę|ę|Ě|ě',
        'f'  => 'F|ƒ',
        'g'  => 'G|Ĝ|ĝ|Ğ|ğ|Ġ|ġ|Ģ|ģ',
        'h'  => 'H|Ĥ|ĥ|Ħ|ħ',
        'i'  => 'I|Í|Ì|Ỉ|Ĩ|Ị|í|ì|ỉ|ĩ|ị|Ì|Í|Î|Ï|ì|í|î|ï|Ĩ|ĩ|Ī|ī|Ĭ|ĭ|Į|į|İ|ı|Ǐ|ǐ',
        'ij' => 'Ĳ|ĳ',
        'j'  => 'J|Ĵ|ĵ',
        'k'  => 'K|Ķ|ķ',
        'l'  => 'L|Ĺ|ĺ|Ļ|ļ|Ľ|ľ|Ŀ|ŀ|Ł|ł',
        'm'  => 'M',
        'n'  => 'N|Ñ|ñ|Ń|ń|Ņ|ņ|Ň|ň|ŉ',
        'o'  => 'O|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ|ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ò|Ó|Ô|Õ|Ö|Ø|ò|ó|ô|õ|ö|ø|Ō|ō|Ŏ|ŏ|Ő|ő|Ơ|ơ|Ǒ|ǒ|Ǿ|ǿ',
        'oe' => 'Œ|œ',
        'p'  => 'P',
        'q'  => 'Q',
        'r'  => 'R|Ŕ|ŕ|Ŗ|ŗ|Ř|ř',
        's'  => 'S|ß|Ś|ś|Ŝ|ŝ|Ş|ş|Š|š|ſ',
        't'  => 'T|Ţ|ţ|Ť|ť|Ŧ|ŧ',
        'u'  => 'U|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự|ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ù|Ú|Û|Ü|ù|ú|û|ü|Ũ|ũ|Ū|ū|Ŭ|ŭ|Ů|ů|Ű|ű|Ų|ų|Ư|ư|Ǔ|ǔ|Ǖ|ǖ|Ǘ|ǘ|Ǚ|ǚ|Ǜ|ǜ',
        'v'  => 'V',
        'w'  => 'W|Ŵ|ŵ',
        'x'  => 'X',
        'y'  => 'Y|Ý|Ỳ|Ỷ|Ỹ|Ỵ|ý|ỳ|ỷ|ỹ|ỵ|ý|ÿ|Ŷ|ŷ|Ÿ',
        'z'  => 'Z|Ź|ź|Ż|ż|Ž|ž'];

      foreach ($unicode as $nonUnicode => $uni) {
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
      }
      $find = [' ', '&', '\r\n', '\n', '+', ',', '//'];
      $str = str_replace($find, '-', $str);
      $find = ['/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/'];
      $replace = ['', '-', ''];
      $str = preg_replace($find, $replace, $str);

      return $str;
    }

    /**
     * @param $str
     * @param int $width
     * @param string $break
     * @return mixed
     */
    public static function wrapWord($str, $width = 70, $break = " ") {
      return preg_replace('#(\S{' . $width . ',})#e', "chunk_split('$1', " . $width . ", '" . $break . "')", $str);
    }

    /**
     * @param $str
     * @param $length
     * @param int $minword
     * @return string
     */
    public static function subString($str, $length, $minword = 3) {
      $sub = '';
      $len = 0;
      foreach (explode(' ', $str) as $word) {
        $part = (($sub != '') ? ' ' : '') . $word;
        $sub .= $part;
        $len += strlen($part);
        if (strlen($word) > $minword && strlen($sub) >= $length) {
          break;
        }
      }

      return $sub;
    }

    /**
     * @param $time
     * @return string
     */
    public static function getTimeDiff($time) {
      $now = time();
      $sec = $now - $time;
      $year = round($sec / (60 * 60 * 24 * 30 * 12));
      if ($year > 0) {
        return $year . " years ago";
      }
      $month = round($sec / (60 * 60 * 24 * 30));
      if ($month > 0) {
        return $month . " months ago";
      }
      $day = round($sec / (60 * 60 * 24));
      if ($day > 0) {
        return $day . " days ago";
      }
      $hour = round($sec / (60 * 60));
      if ($hour > 0) {
        return $hour . " hours ago";
      }
      $min = round($sec / (60));
      if ($min > 0) {
        return $min . " minutes ago";
      }

      return $sec . " seconds ago";
    }

    /**
     * @param $str
     * @param $maxLength
     * @return mixed|string
     */
    public static function generateSlug($str, $maxLength) {
      $str = strtolower($str);
      $str = preg_replace("/[^a-z0-9\s-]/", "", $str);
      $str = trim(preg_replace("/[\s-]+/", " ", $str));
      $str = trim(substr($str, 0, $maxLength));
      $str = preg_replace("/\s/", "-", $str);

      return $str;
    }

    /**
     * Convert  array to array with index
     * @param array $arr
     * @param string $index use column as index of the return array[index]
     * @param array $keys keys to get
     * @return array
     */
    public static function indexArray($arr = [], $index = null, $keys = null) {
      $newArr = [];
      if (isset($index)) {
        if (!empty($keys)) {
          $newArr[$arr[$index]] = array_intersect_key($arr, array_flip($keys));
        } else {
          $newArr[$arr[$index]] = $arr;
        }
      } else {
        if (!empty($keys)) {
          $newArr = array_intersect_key($arr, array_flip($keys));
        } else {
          $newArr = $arr;
        }
      }

      return $newArr;
    }
  }