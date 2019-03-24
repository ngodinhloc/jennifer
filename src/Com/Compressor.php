<?php

namespace Jennifer\Com;
/**
 * Compressor class: compress html, css, js (string and files)
 * @package Jennifer\Com
 */
class Compressor
{
    /**
     * Remove white space between html tags
     * @param $html
     * @return string
     */
    public static function compressHTML($html)
    {
        $html = preg_replace('/(?<=>)\s+(?=<)/', "", $html);

        return $html;
    }
}