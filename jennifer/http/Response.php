<?php

namespace jennifer\http;

use jennifer\com\Common;

class Response
{
    /**
     * Redirect to another view with para
     * @param string $url requires full /module/view/
     * @param array $paras
     */
    public function redirect($url, $paras = [])
    {
        $str = Common::arrayToParas($paras);
        Common::redirectTo($url . $str);
    }

    /**
     * @param $message
     */
    public function error($message)
    {
        die($message);
    }
}