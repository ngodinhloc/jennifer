<?php
namespace cons;
require_once(DOC_ROOT . '/plugins/facebook/autoload.php');
use com\Common;
use thedaysoflife\Admin;
use sys\System;
use Facebook;

class ControllerFacebook extends Controller {
  protected $requiredPermission = ["admin"];
  private $admin;
  private $fb;

  public function __construct() {
    parent::__construct();

    $this->admin = new Admin();
    $this->fb    = new Facebook\Facebook(['app_id'                => FB_APPID,
                                          'app_secret'            => FB_SECRET,
                                          'default_graph_version' => 'v2.8',]);
  }

  /**
   * Post days to facebook
   */
  public function ajaxPostToFacebook() {
    $appAccessToken = System::getSession("FB_appAccessToken");
    $id             = (int)$this->post['id'];
    $type           = $this->post['type'];
    if ($appAccessToken) {
      $day = $this->admin->getDayById($id);
      switch($type) {
        case FB_TEXT:
          $attachment = $this->fbText($day);
          $response   = $this->fb->post('/' . FB_PAGEID . '/feed', $attachment, $appAccessToken);
          $postID     = $response->getGraphNode();
          if ($postID) {
            $this->admin->updateFB($id, $type);
            $this->response(["status" => "OK", "id" => $id]);
          }
          break;

        case FB_FEED:
          $attachment = $this->fbFeed($day);
          $response   = $this->fb->post('/' . FB_PAGEID . '/feed', $attachment, $appAccessToken);
          $postID     = $response->getGraphNode();
          if ($postID) {
            $this->admin->updateFB($id, $type);
            $this->response(["status" => "OK", "id" => $id, "data" => "OK"]);
          }
          break;

        case FB_LINK:
          $attachment = $this->fbLink($day);
          $response   = $this->fb->post('/' . FB_PAGEID . '/feed', $attachment, $appAccessToken);
          $postID     = $response->getGraphNode();
          if ($postID) {
            $this->admin->updateFB($id, $type);
            $this->response(["status" => "OK", "id" => $id, "data" => "OK"]);
          }
          break;

        case FB_ALBUM:
          $photos = explode(',', $day['photos']);
          $title  = $day['day'] . '/' . $day['month'] . '/' . $day['year'] . ': ' . $day["title"] .
                    " (by " . $day["username"] . ")";
          $status = "NO";
          if (sizeof($photos) > 0) {
            $albumData = $this->fbAlbum($day);
            $newAlbum  = $this->fb->post("/" . FB_PAGEID . "/albums", $albumData, $appAccessToken);
            $album     = $newAlbum->getDecodedBody();
            if (isset($album["id"])) {
              foreach ($photos as $i => $name) {
                $photoName = html_entity_decode($title . " - " . ($i + 1), ENT_COMPAT, "UTF-8");
                $photoURL  = Common::getPhotoURL($name, PHOTO_FULL_NAME);
                $photoData = ['message' => $photoName, 'url' => $photoURL];
                $newPhoto  = $this->fb->post('/' . $album["id"] . '/photos', $photoData, $appAccessToken);
                $photo     = $newPhoto->getDecodedBody();
                if (isset($photo["id"])) {
                  $this->admin->updateFB($id, $type);
                  $status = "OK";
                }
              }
            }
          }
          $this->response(["status" => $status, "id" => $id, "data" => $status]);
          break;
      }
    }
    else {
      $permissions = ['manage_pages', 'publish_actions'];
      $helper      = $this->fb->getRedirectLoginHelper();
      $loginUrl    = $helper->getLoginUrl(SITE_URL . '/back/days/', $permissions);
      $this->response(["status" => "login", "id" => $id,
                       "data"   => '<a href="' . $loginUrl . '">FBLogin</a>']);
    }
  }

  /**
   * @param array $day
   * @return array
   */
  private function fbText($day) {
    $content    = stripslashes($day["content"]);
    $content    = str_replace("<li>", "\n", $content);
    $content    = str_replace("<br>", "\n", $content);
    $content    = str_replace("&nbsp;", " ", $content);
    $content    = strip_tags($content);
    $msg        = $day['day'] . '/' . $day['month'] . '/' . $day['year'] . ': ' . $day["title"] .
                  " (by " . $day["username"] . "): \n" . $content;
    $msg        = html_entity_decode($msg, ENT_COMPAT, "UTF-8");
    $attachment = [
      'message' => $msg,
    ];

    return $attachment;
  }

  /**
   * @param array $day
   * @return array
   */
  private function fbFeed($day) {
    $uri         = LIST_URL . $day['id'] . '/' . $day['day'] . $day['month'] . $day['year'] . '-' .
                   $day['slug'] . URL_EXT;
    $msg         = html_entity_decode($day['day'] . '/' . $day['month'] . '/' . $day['year'] . ': ' .
                                      $day['title'] . ' (by ' . $day['username'] . ')', ENT_COMPAT, "UTF-8");
    $title       = html_entity_decode($day['day'] . '/' . $day['month'] . '/' . $day['year'] . ': ' .
                                      $day['title'], ENT_COMPAT, "UTF-8");
    $content     = stripslashes($day["content"]);
    $content     = str_replace("<li>", " - ", $content);
    $content     = str_replace("<br>", "\n", $content);
    $content     = str_replace("&nbsp;", " ", $content);
    $content     = Common::subString(strip_tags($content), 300, 3);
    $desc        = html_entity_decode($content, ENT_COMPAT, "UTF-8");
    $photos      = explode(',', $day['photos']);
    $pic         = Common::getPhotoURL($photos[0], PHOTO_TITLE_NAME);
    $action_name = 'View on Thedaysoflife.com';
    $action_link = LIST_URL . $day['id'] . '/' . $day['day'] . $day['month'] . $day['year'] . '-' .
                   $day['slug'] . URL_EXT;
    $attachment  = [
      'message'     => $msg,
      'name'        => $title,
      'link'        => $uri,
      'description' => $desc,
      'picture'     => $pic,
      'actions'     => json_encode(['name' => $action_name, 'link' => $action_link]),
    ];

    return $attachment;
  }

  /**
   * @param array $day
   * @return array
   */
  private function fbLink($day) {
    $uri        = LIST_URL . $day['id'] . '/' . $day['day'] . $day['month'] . $day['year'] . '-' . $day['slug'] .
                  URL_EXT;
    $content    = stripslashes($day["content"]);
    $content    = str_replace("<li>", "\n", $content);
    $content    = str_replace("<br>", "\n", $content);
    $content    = str_replace("&nbsp;", " ", $content);
    $content    = strip_tags($content);
    $msg        = $day['day'] . '/' . $day['month'] . '/' . $day['year'] . ': ' . $day["title"] . " (by " .
                  $day["username"] . "): \n\n" . $content;
    $msg        = html_entity_decode($msg, ENT_COMPAT, "UTF-8");
    $attachment = [
      'type'    => 'photo',
      'message' => $msg,
      'link'    => $uri,
    ];

    return $attachment;
  }

  /**
   * @param array $day
   * @return array
   */
  private function fbAlbum($day) {
    $title      = $day['day'] . '/' . $day['month'] . '/' . $day['year'] . ': ' . $day["title"] . " (by " .
                  $day["username"] . ")";
    $content    = stripslashes($day["content"]);
    $content    = str_replace("<li>", "\n", $content);
    $content    = str_replace("<br>", "\n", $content);
    $content    = str_replace("&nbsp;", " ", $content);
    $content    = strip_tags($content);
    $album_name = html_entity_decode($title, ENT_COMPAT, "UTF-8");
    $album_desc = html_entity_decode($content, ENT_COMPAT, "UTF-8");
    $album_data = [
      "name"    => $album_name,
      "message" => $album_desc,
    ];

    return $album_data;
  }
}