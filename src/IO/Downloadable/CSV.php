<?php

namespace Jennifer\IO\Downloadable;

use Jennifer\IO\Downloadable;
use Jennifer\IO\DownloadableInterface;

/**
 * Class CSV
 * @package Jennifer\File
 */
class CSV extends Downloadable implements DownloadableInterface
{
    /** @var string $name */
    protected $name;
    /** @var array $data */
    protected $data;

    /**
     * CSV constructor.
     * @param string $name
     * @param array $data 2 dimensional array
     */
    public function __construct($name = "", $data = [])
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * Output the csv file for download
     */
    public function download()
    {
        $this->headers($this->name);
        echo($this->arrayToCSV($this->data));
        exit();
    }

    /**
     * Convert data (array) to csv ready content
     * @param array $array 2 dimensional array
     * @return null|string
     */
    protected function arrayToCSV($array)
    {
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