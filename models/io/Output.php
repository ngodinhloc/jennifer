<?php
namespace io;

use com\Compressor;
use file\CSV;

class Output implements OutputInterface {

  /**
   * Render html
   * @param string $html
   * @param bool $compress
   */
  public function html($html = "", $compress = false) {
    if ($compress) {
      $html = Compressor::compressHTML($html);
    }
    echo($html);
  }

  /**
   * Response to ajax request
   * @param array|string $data
   * @param bool $json
   * @param int $jsonOpt
   */
  public function ajax($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES) {
    if (is_array($data)) {
      if ($json) {
        header('Content-Type: application/json');
        echo(json_encode($data, $jsonOpt));
        exit;
      }
      echo(json_encode($data, $jsonOpt));
      exit;
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