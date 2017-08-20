<?php
namespace file;
use file\Downloadable;

class CSV extends Downloadable {
  /**
   * Output the csv file for download
   * @param array $data 2 dimensional array
   * @param string $fileName
   */
  public function file($data = [], $fileName = "") {
    $this->headers($fileName);
    echo($this->arrayToCSV($data));
    exit();
  }

  /**
   * Convert data (array) to csv ready content
   * @param array $array 2 dimensional array
   * @return null|string
   */
  protected function arrayToCSV($array) {
    if (count($array) == 0) {
      return null;
    }
    ob_start();
    $df = fopen("php://output", 'w');
    fputcsv($df, array_keys(reset($array)));
    foreach ($array as $row) {
      fputcsv($df, $row);
    }
    fclose($df);

    return ob_get_clean();
  }
}