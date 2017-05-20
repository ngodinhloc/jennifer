<?php
  namespace file;
  class CSV {
    /**
     * Convert data (array) to csv ready content
     * @param array $array 2 dimensional array
     * @return null|string
     */
    private function arrayToCSV($array) {
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

    /**
     * Send headers to browser
     * @param string $fileName
     */
    private function sendHeaders($fileName = "") {
      // disable caching
      $now = gmdate("D, d M Y H:i:s");
      header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
      header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
      header("Last-Modified: {$now} GMT");

      // force download
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");

      // disposition / encoding on response body
      header("Content-Disposition: attachment;filename={$fileName}");
      header("Content-Transfer-Encoding: binary");
    }

    /**
     * Ouput the csv file for download
     * @param array $data 2 dimensional array
     * @param string $fileName
     */
    public static function getFile($data = [], $fileName = "") {
      self::sendHeaders($fileName);
      echo(self::arrayToCSV($data));
      exit();
    }
  }