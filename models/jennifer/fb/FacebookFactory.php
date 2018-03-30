<?php

namespace jennifer\fb;
require_once(DOC_ROOT . '/plugins/facebook/autoload.php');

use Facebook;
use jennifer\sys\Config;

/**
 * Class FacebookFactory
 * @package jennifer\fb
 */
class FacebookFactory {
  /**
   * @return Facebook\Facebook
   */
  public function createFacebook() {
    $facebook = new Facebook\Facebook(['app_id'                => Config::FB_APPID,
                                       'app_secret'            => Config::FB_SECRET,
                                       'default_graph_version' => 'v2.8',]);

    return $facebook;
  }
}