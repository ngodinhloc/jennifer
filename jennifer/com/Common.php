<?php

namespace jennifer\com;

use jennifer\html\Element;
use jennifer\sys\Globals;

/**
 * Class Common: static helper methods
 * @package jennifer\com
 */
class Common {
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
  public static function getDayOptions($day = 0) {
    $range = range(1, 31);
    $arr   = [];
    foreach ($range as $val) {
      $arr[$val] = $val;
    }
    $options = Element::options($arr, $day, 'select');

    return $options;
  }

  /**
   * @param $month
   * @return string
   */
  public static function getMonthOptions($month = 0) {
    $range = range(1, 12);
    $arr   = [];
    foreach ($range as $val) {
      $arr[$val] = $val;
    }
    $options = Element::options($arr, $month, 'select');

    return $options;
  }

  /**
   * @param $year
   * @return string
   */
  public static function getYearOptions($year = 0) {
    $range = range(date('Y'), date('Y') - 100);
    $arr   = [];
    foreach ($range as $val) {
      $arr[$val] = $val;
    }
    $options = Element::options($arr, $year, 'select');

    return $options;
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
    $find    = [' ', '&', '\r\n', '\n', '+', ',', '//'];
    $str     = str_replace($find, '-', $str);
    $find    = ['/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/'];
    $replace = ['', '-', ''];
    $str     = preg_replace($find, $replace, $str);

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
      $sub  .= $part;
      $len  += strlen($part);
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
    $now  = time();
    $sec  = $now - $time;
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
      }
      else {
        $newArr[$arr[$index]] = $arr;
      }
    }
    else {
      if (!empty($keys)) {
        $newArr = array_intersect_key($arr, array_flip($keys));
      }
      else {
        $newArr = $arr;
      }
    }

    return $newArr;
  }

  /**
   * Start ob compression
   */
  public static function obStart() {
    ob_start("ob_gzhandler");
  }

  /**
   * Output ob
   */
  public static function obFlush() {
    ob_flush();
    flush();
  }

  /**
   * @param $newpage
   */
  public static function redirectTo($newpage) {
    $host = Globals::server("HTTP_HOST");
    $uri  = rtrim(dirname(Globals::server("PHP_SELF")), '/\\');
    header("Location: http://{$host}{$uri}{$newpage}");
    exit();
  }

  /**
   * @param $uri
   */
  public static function jsRedirect($uri) {
    echo("<script>window.location.href = '{$uri}'</script>");
  }

  /**
   * @param $filename
   * @return mixed
   */
  public static function getFileExtension($filename) {
    $pathInfo = pathinfo($filename);

    return $pathInfo['extension'];
  }
}