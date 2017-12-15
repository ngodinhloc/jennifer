<?php
namespace jennifer\fb;
require_once(DOC_ROOT . '/plugins/facebook/autoload.php');

use Facebook;

/**
 * Class FacebookFactory
 * @package fb
 */
class FacebookFactory {
  /**
   * @return Facebook\Facebook
   */
  public function createFacebook() {
    $facebook = new Facebook\Facebook(['app_id'                => FacebookHelper::FB_APPID,
                                       'app_secret'            => FacebookHelper::FB_SECRET,
                                       'default_graph_version' => 'v2.8',]);

    return $facebook;
  }
}