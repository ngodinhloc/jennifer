<?php
namespace back;
require_once(DOC_ROOT . '/plugins/facebook/autoload.php');
use sys\System;
use view\Back;
use core\Admin;
use Facebook;

class days extends Back {
  protected $title = "Dashboard :: Days";
  protected $contentTemplate = "days";

  public function __construct() {
    parent::__construct();

    $admin              = new Admin();
    $days               = $admin->getDayList(1);
    $this->data["days"] = $days;

    // loin facebook
    if (!System::getSession("FB_appAccessToken")) {
      $fb     = new Facebook\Facebook(['app_id'                => FB_APPID,
                                       'app_secret'            => FB_SECRET,
                                       'default_graph_version' => 'v2.8',]);
      $helper = $fb->getRedirectLoginHelper();
      try {
        $accessToken = $helper->getAccessToken();
        if (isset($accessToken)) {
          $client = $fb->getOAuth2Client();
          try {
            $accessToken = $client->getLongLivedAccessToken($accessToken);
          }
          catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo $e->getMessage();
            exit;
          }
          $response = $fb->get('/me/accounts', (string)$accessToken);
          foreach ($response->getDecodedBody() as $allPages) {
            foreach ($allPages as $page) {
              if (isset($page['id']) && $page['id'] == FB_PAGEID) { // Suppose you save it as this variable
                $appAccessToken = (string)$page['access_token'];
                System::setSession("FB_appAccessToken", $appAccessToken);
                break;
              }
            }
          }
        }
      }
      catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo $e->getMessage();
        exit;
      }
    }
  }
}