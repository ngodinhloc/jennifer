<?php

namespace Jennifer\IO;
/**
 * Class Downloadable
 * @package Jennifer\File
 */
class Downloadable
{
    /**
     * Send headers to browser
     * @param string $fileName
     * @param array $options
     */
    protected function headers($fileName = "", $options = ["cache" => false])
    {
        if (isset($options["cache"]) && $options["cache"] == false) {
            // disable caching
            $now = gmdate("D, d M Y H:i:s");
            header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
            header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
            header("Last-Modified: {$now} GMT");
        }

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$fileName}");
        header("Content-Transfer-Encoding: binary");
    }
}