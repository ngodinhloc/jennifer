<?php

namespace jennifer\fb;
require_once(DOC_ROOT . '/plugins/facebook/autoload.php');

use Facebook;
use Facebook\Exceptions\FacebookSDKException;

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
        $facebook = new Facebook\Facebook(['app_id' => getenv("FB_APPID"),
            'app_secret' => getenv("FB_SECRET"),
            'default_graph_version' => 'v2.8',]);

        return $facebook;
    }
}