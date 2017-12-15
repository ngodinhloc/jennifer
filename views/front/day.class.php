<?php
namespace front;

use jennifer\fb\FacebookHelper;
use jennifer\html\jobject\FlexSlider;
use jennifer\sys\Globals;
use jennifer\view\ViewInterface;
use thedaysoflife\com\Com;
use thedaysoflife\model\User;
use thedaysoflife\view\ViewFront;

class day extends ViewFront implements ViewInterface {
  protected $contentTemplate = "day";

  public function __construct() {
    parent::__construct();
    $this->user = new User();
  }

  public function prepare() {
    $user       = new User();
    $topDays    = $user->getRightTopDays();
    $this->data = ["topDays" => $topDays,];
    $id         = $this->hasPara("day");
    if ($id) {
      $day = $user->getDayById($id);
      if ($day) {
        $this->uri         = Com::getDayLink($day);
        $this->title       = Com::getDayTitle($day);
        $this->description = Com::getDayDescription($day);
        $this->keyword     = $day['title'];
        $slider            = "";
        if (trim($day['photos']) != "") {
          $photoArray  = explode(',', trim($day['photos']));
          $fullPhotos  = Com::getPhotoArray($photoArray, PHOTO_FULL_NAME);
          $thumbPhotos = Com::getPhotoArray($photoArray, PHOTO_THUMB_NAME);
          $flexSlider  = new FlexSlider([], ["fullPhotos"  => $fullPhotos,
                                             "thumbPhotos" => $thumbPhotos]);
          $slider      = $flexSlider->render();
          $this->registerMetaFiles($flexSlider);
        }
        $photoURL  = Com::getFirstPhotoURL($day);
        $ipAddress = Globals::todayIPAddress();
        $likeIP    = explode('|', $day['like_ip']);

        $data = ["uri"          => $this->uri,
                 "title"        => $this->title,
                 "time"         => Com::getTimeDiff($day['time']),
                 "photoURL"     => $photoURL,
                 "authorLink"   => Com::getSearchLink($day['username']),
                 "locationLink" => $day['location'] != '' ? Com::getSearchLink($day['location']) : false,
                 "dateLink"     => Com::getSearchLink($day['month'] . '/' . $day['year'], false),
                 "liked"        => in_array($ipAddress, $likeIP),
                 "slider"       => $slider,
                 "comments"     => $user->getComments($id)];

        $dayData = array_merge($day, $data);

        $relatedDays = $user->getRightRelatedDays(["day"      => (int)$day['day'],
                                                   "month"    => (int)$day['month'],
                                                   "year"     => (int)$day['year'],
                                                   "location" => $day['location']]);
        $this->data  = ["day"         => $dayData,
                        "relatedDays" => $relatedDays != "" ? $relatedDays : "No related days found",
                        "topDays"     => $topDays,];

        $this->addMetaTag("<meta property='fb:admins' content='" . FacebookHelper::FB_PAGEID . "'/>");
        $this->addMetaTag("<meta property='og:type' content='article'/>");
        $this->addMetaTag("<meta property='og:url' content='{$this->uri}'/>");
        $this->addMetaTag("<meta property='og:title' content='{$this->title}'/>");
        $this->addMetaTag("<meta property='og:description' content='{$this->description}'/>");
        $this->addMetaTag("<meta property='og:image' content='{$photoURL}'/>");
        $this->addMetaFile(SITE_URL . "/plugins/jquery/jquery.autosize.min.js");
      }
    }
  }
}