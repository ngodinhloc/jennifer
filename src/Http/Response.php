<?php

namespace Jennifer\Http;

use Jennifer\Com\Common;

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
     * @param $code
     */
    public function error($message, $code)
    {
        die("Error code {$code}: {$message}");
    }
}