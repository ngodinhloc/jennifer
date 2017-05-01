<?php
namespace front;

use com\Com;
use core\View;
use sys\System;
use view\Front;

class day extends Front {
  protected $contentTemplate = "day";

  public function __construct() {
    parent::__construct();

    $view       = new View();
    $topDays    = $view->getRightTopDay();
    $this->data = ["pageTitle" => $this->title, "pageDesc" => $this->description, "topDays" => $topDays,];
    $id         = $this->hasPara("day");
    if ($id) {
      $days = $view->getDayById($id);
      if ($days) {
        $likeIP    = explode('|', $days['like_ip']);
        $time      = Com::getTimeDiff($days['time']);
        $ipaddress = System::getTodayIPaddress();
        $day       = (int)$days['day'];
        $month     = (int)$days['month'];
        $year      = (int)$days['year'];
        $location  = $days['location'];
        $uri       = Com::getDayLink($days);
        $photos    = trim($days['photos']);
        $imgURL    = "";
        if ($photos != "") {
          $imgs   = explode(',', $photos);
          $imgURL = Com::getPhotoURL($imgs[0], PHOTO_FULL_NAME);
        }
        $this->title       = Com::getDayTitle($days);
        $this->description = Com::getDayDescription($days);
        $this->keyword     = $days['title'];
        $comments          = $view->getComments($id);
        $relatedDays       = $view->getRightRelatedDay($day, $month, $year, $location);
        $this->data        = ["pageTitle"   => $this->title,
                              "pageDesc"    => $this->description,
                              "imgURL"      => $imgURL,
                              "uri"         => $uri,
                              "days"        => $days,
                              "photos"      => $photos,
                              "likeIP"      => $likeIP,
                              "time"        => $time,
                              "ipaddress"   => $ipaddress,
                              "comments"    => $comments,
                              "relatedDays" => $relatedDays,
                              "topDays"     => $topDays,];
        $this->addMetaTag("<meta property='fb:admins' content='" . FB_PAGEID . "'/>");
        $this->addMetaTag("<meta property='og:type' content='article'/>");
        $this->addMetaTag("<meta property='og:url' content='{$uri}'/>");
        $this->addMetaTag("<meta property='og:title' content='{$this->title}'/>");
        $this->addMetaTag("<meta property='og:image' content='{$imgURL}'/>");
        $this->addMetaTag("<meta property='og:description' content='{$this->description}'/>");
      }
    }
  }
}