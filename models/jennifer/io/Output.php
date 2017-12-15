<?php
namespace jennifer\io;

use jennifer\file\CSV;

/**
 * Class Output: output class
 * @package io
 */
class Output implements OutputInterface {
  const BUFFER_SIZE = 10000;

  /**
   * Output html
   * @param string $html
   * @param bool $chunk chunk the long string
   */
  public function html($html = "", $chunk = true) {
    if ($chunk) {
      $splits = str_split($html, self::BUFFER_SIZE);
      foreach ($splits as $split) {
        echo($split);
      }
      exit();
    }

    echo($html);
    exit();
  }

  /**
   * Response to ajax request
   * @param array|string $data
   * @param bool $json
   * @param int $jsonOpt
   */
  public function ajax($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES) {
    if (is_array($data)) {
      if ($json == true) {
        header('Content-Type: application/json');
      }
      echo(json_encode($data, $jsonOpt));
      exit();
    }

    echo($data);
    exit();
  }

  /**
   * Output csv file for download
   * @param array $data
   * @param string $fileName
   */
  public function csv($data = [], $fileName = "") {
    $csv = new CSV();
    $csv->file($data, $fileName);
  }
}