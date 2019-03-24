<?php

namespace Jennifer\Facebook;

use Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Jennifer\Sys\Config;

/**
 * Class FacebookFactory
 * @package Jennifer\Facebook
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