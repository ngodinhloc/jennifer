<?php
namespace jennifer\html;

use jennifer\html\jobject\ClockPicker;
use jennifer\html\jobject\ColorPicker;
use jennifer\html\jobject\DatePicker;
use jennifer\html\jobject\FileUploader;

/**
 * Class Jquery
 * @package html
 */
class Jquery {
  public static function colorPicker($attr, $data) {
    $colorPicker = new ColorPicker($attr, $data);

    return $colorPicker->render();
  }

  public static function datePicker($attr, $data) {
    $datePicker = new DatePicker($attr, $data);

    return $datePicker->render();
  }

  public static function clockPicker($attr, $data) {
    $clockPicker = new ClockPicker($attr, $data);

    return $clockPicker->render();
  }

  public function fileUploader($attr, $data) {
    $fileUploader = new FileUploader($attr, $data);

    return $fileUploader;
  }
}