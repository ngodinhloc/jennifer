<?php

namespace jennifer\fb;
require_once(DOC_ROOT . '/plugins/facebook/autoload.php');

use Facebook;
use Facebook\Exceptions\FacebookSDKException;
use jennifer\sys\Config;

/**
 * Class FacebookFactory
 * @package jennifer\fb
 */
class FacebookFactory
{
    /**
     * @return Facebook\Facebook
     * @throws FacebookSDKException
     */
    public function createFacebook()
    {
        $facebook = new Facebook\Facebook(['app_id' => Config::getConfig("FB_APPID"),
            'app_secret' => Config::getConfig("FB_SECRET"),
            'default_graph_version' => 'v2.8',]);

        return $facebook;
    }
}