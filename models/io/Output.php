<?php
namespace io;

use file\CSV;

/**
 * Class Output: output class
 * @package io
 */
class Output implements OutputInterface {
  /**
   * Output html
   * @param string $html
   */
  public function html($html = "") {
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
    if ($json == true) {
      header('Content-Type: application/json');
      echo(json_encode($data, $jsonOpt));
      exit();
    }
    if (is_array($data)) {
      echo(json_encode($data, $jsonOpt));
      exit();
    }
    echo $data;
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