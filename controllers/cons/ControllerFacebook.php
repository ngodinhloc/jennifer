<?php
namespace cons;
require_once(DOC_ROOT . '/plugins/facebook/src/facebook.php');
use com\Com;
use core\Admin;
use Facebook;

class ControllerFacebook extends Controller {
  protected $requiredPermission = ["admin"];
  private $admin;
  private $fb;
  private $fbUser;
  private $fbPageID = FB_PAGEID;
  private $fbPermissions = 'manage_pages, publish_actions';

  public function __construct() {
    parent::__construct();

    $this->admin  = new Admin();
    $this->fb     = new Facebook(['appId' => FB_APPID, 'secret' => FB_SECRET]);
    $this->fbUser = $this->fb->getUser();
  }

  public function ajaxPostToFacebook($para) {
    $type = $para['type'];
    $id   = (int)$para['id'];
    $day  = $this->admin->getDayById($id);

    if ($this->fbUser) {
      $result            = $this->fb->api("/me/accounts");
      $page_access_token = "";

      // loop through all pages to find the right one
      if (!empty($result["data"])) {
        foreach ($result["data"] as $page) {
          if ($page["id"] == $this->fbPageID) {
            $page_access_token = $page["access_token"];
          }
        }
      }
      // pageid not found:
      if ($page_access_token == "") {
        echo "No access_token. Check pageId: " . $this->fbPageID;
      }
      else {
        // pageid found, access_token gained
        $this->fb->setAccessToken($page_access_token);

        // post as text
        if ($type == FB_TEXT) {
          try {
            $content     = stripslashes($day["content"]);
            $content     = str_replace("<li>", "\n", $content);
            $content     = str_replace("<br>", "\n", $content);
            $content     = str_replace("&nbsp;", " ", $content);
            $content     = strip_tags($content);
            $msg         = $day['day'] . '/' . $day['month'] . '/' . $day['year'] . ': ' . $day["title"] . " (by " .
                           $day["username"] . "): \n" . $content;
            $msg         = html_entity_decode($msg, ENT_COMPAT, "UTF-8");
            $action_name = "View on Thedaysoflife.com";
            $action_link = LIST_URL . $day["id"] . '/' . $day['day'] . $day['month'] . $day['year'] . '-' .
                           $day["slug"] . URL_EXT;
            $attachment  = [
              'message' => $msg,
            ];
            $result      = $this->fb->api("/" . $this->fbPageID . "/feed", "post", $attachment);
            if ($result) {
              $this->admin->updateFB($id, $type);
              echo "OK";
            }
          }
          catch (FacebookApiException $e) {
            echo $e->getMessage();
          }
        }

        // post as feed
        if ($type == FB_FEED) {
          try {
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
            $content     = Com::subString(strip_tags($content), 300, 3);
            $desc        = html_entity_decode($content, ENT_COMPAT, "UTF-8");
            $photos      = explode(',', $day['photos']);
            $pic         = Com::getPhotoURL($photos[0], PHOTO_TITLE_NAME);
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
            $result      = $this->fb->api('/' . $this->fbPageID . '/feed', 'post', $attachment);
            if ($result) {
              $this->admin->updateFB($id, $type);
              echo "OK";
            }
          }
          catch (FacebookApiException $e) {
            echo $e->getMessage();
          }
        }

        // post as link
        if ($type == FB_LINK) {
          try {
            $uri     = LIST_URL . $day['id'] . '/' . $day['day'] . $day['month'] . $day['year'] . '-' . $day['slug'] .
                       URL_EXT;
            $content = stripslashes($day["content"]);
            $content = str_replace("<li>", "\n", $content);
            $content = str_replace("<br>", "\n", $content);
            $content = str_replace("&nbsp;", " ", $content);
            $content = strip_tags($content);
            $msg     = $day['day'] . '/' . $day['month'] . '/' . $day['year'] . ': ' . $day["title"] . " (by " .
                       $day["username"] . "): \n\n" . $content;
            $msg     = html_entity_decode($msg, ENT_COMPAT, "UTF-8");

            $attachment = [
              'type'    => 'photo',
              'message' => $msg,
              'link'    => $uri,
            ];

            $result = $this->fb->api('/' . $this->fbPageID . '/links/', 'post', $attachment);
            if ($result) {
              $this->admin->updateFB($id, $type);
              echo "OK";
            }
          }
          catch (FacebookApiException $e) {
            echo $e->getMessage();
          }
        }

        // post as album
        if ($type == FB_ALBUM) {
          try {
            $photos = explode(',', $day['photos']);
            // there are photos to upload
            if (sizeof($photos) > 0) {
              $title      = $day['day'] . '/' . $day['month'] . '/' . $day['year'] . ': ' . $day["title"] . " (by " .
                            $day["username"] . ")";
              $content    = stripslashes($day["content"]);
              $content    = str_replace("<li>", "\n", $content);
              $content    = str_replace("<br>", "\n", $content);
              $content    = str_replace("&nbsp;", " ", $content);
              $content    = strip_tags($content);
              $album_name = html_entity_decode($title, ENT_COMPAT, "UTF-8");
              $album_desc = html_entity_decode($content, ENT_COMPAT, "UTF-8");

              //$user_profile = $this->fb->api("/$pageid");
              $album_data = [
                "name"    => $album_name,
                "message" => $album_desc,
              ];
              $new_album  = $this->fb->api("/$this->fbPageID/albums", "post", $album_data);
              $album_id   = $new_album['id'];

              $this->fb->setFileUploadSupport(true);

              foreach ($photos as $i => $name) {
                $photo_name = html_entity_decode($title . " - " . ($i + 1), ENT_COMPAT, "UTF-8");
                $pic        = Com::getPhotoURL($name, PHOTO_FULL_NAME);
                $photo      = [
                  'message' => $photo_name,
                  'url'     => $pic,
                ];
                $pho        = $this->fb->api('/' . $album_id . '/photos', 'POST', $photo);
                if (isset($pho['id'])) {
                  $this->admin->updateFB($id, $type);
                  $status = "OK";
                }
              }
            }
            echo $status;
          }
          catch (FacebookApiException $e) {
            echo $e->getMessage();
          }
        }
      }
    }
    else {
      $fbLoginURL = $this->fb->getLoginUrl(['redirect_uri' => SITE_URL . '/back/days/',
                                            'redirect-uri' => SITE_URL . '/back/days/',
                                            'scope'        => $this->fbPermissions]);
      echo '<a href="' . $fbLoginURL . '">FBLogin</a>';

    }
  }
}