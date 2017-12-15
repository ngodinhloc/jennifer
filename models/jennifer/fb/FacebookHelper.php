<?php
namespace jennifer\fb;

use Facebook;
use jennifer\html\Element;
use jennifer\sys\Globals;

/**
 * Class FacebookHelper: create Facebook object and do things
 * @package fb
 */
class FacebookHelper {
  /** @var Facebook\Facebook */
  public $fb;
  // Facebook configurations
  const FB_APPID   = '*****';
  const FB_SECRET  = '*****';
  const FB_PAGEID  = '*****';
  const FB_ALBUMID = '*****';
  // Post type
  const FB_TEXT  = 'text';
  const FB_FEED  = 'feed';
  const FB_ALBUM = 'album';
  const FB_LINK  = 'link';

  public function __construct() {
    $factory  = new FacebookFactory();
    $this->fb = $factory->createFacebook();
  }

  /**
   * Login to Facebook page and set session
   */
  public function fbLogin() {
    if (!Globals::session("FB_appAccessToken")) {
      $helper = $this->fb->getRedirectLoginHelper();
      try {
        $accessToken = $helper->getAccessToken();
        if (isset($accessToken)) {
          $client = $this->fb->getOAuth2Client();
          try {
            $accessToken = $client->getLongLivedAccessToken($accessToken);
          }
          catch (Facebook\Exceptions\FacebookSDKException $e) {
            die($e->getMessage());
          }
          $response = $this->fb->get('/me/accounts', (string)$accessToken);
          foreach ($response->getDecodedBody() as $allPages) {
            foreach ($allPages as $page) {
              // if page found then set session
              if (isset($page['id']) && $page['id'] == FacebookHelper::FB_PAGEID) {
                $appAccessToken = (string)$page['access_token'];
                Globals::setSession("FB_appAccessToken", $appAccessToken);
                break;
              }
            }
          }
        }
      }
      catch (Facebook\Exceptions\FacebookSDKException $e) {
        die($e->getMessage());
      }
    }
  }

  /**
   * Get FB act options
   * @param string $selected selected option
   * @return string
   */
  public static function getActOptions($selected) {
    $array   = ["None"  => "",
                "Album" => self::FB_ALBUM,
                "Link"  => self::FB_LINK,
                "Feed"  => self::FB_FEED,
                "Text"  => self::FB_TEXT];
    $options = Element::options($array, $selected);

    return $options;
  }
}