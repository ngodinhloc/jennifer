<?php

namespace jennifer\com;
/**
 * Compressor class: compress html, css, js (string and files)
 * @package jennifer\com
 */
class Compressor {
  /**
   * Remove white space between html tags
   * @param $html
   * @return string
   */
  public static function compressHTML($html) {
    $html = preg_replace('/(?<=>)\s+(?=<)/', "", $html);

    return $html;
  }
}