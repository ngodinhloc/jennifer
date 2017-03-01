<?php
namespace front;
use view\Front;
use sys\System;
use com\Com;
use core\View;

class day extends Front {
  protected $contentTemplate = "day";

  public function __construct() {
    parent::__construct();

    $id = System::getDayPara();
    if ($id > 0) {
      $view = new View();
      $days = $view->getDayById($id);
      if (isset($days['id'])) {
        $like      = (int)$days['like'];
        $likeIP    = explode('|', $days['like_ip']);
        $time      = Com::getTimeDiff($days['time']);
        $ipaddress = System::getTodayIPaddress();
        $day       = (int)$days['day'];
        $month     = (int)$days['month'];
        $year      = (int)$days['year'];
        $location  = $days['location'];
        $uri       = LIST_URL . $days['id'] . '/' . $days['day'] . $days['month'] . $days['year'] . '-' .
                     $days['slug'] . URL_EXT;
        $photos    = trim($days['photos']);
        $imgURL    = "";
        if ($photos != "") {
          $imgs   = explode(',', $photos);
          $img    = $imgs[0];
          $imgURL = Com::getPhotoURL($img, PHOTO_FULL_NAME);
        }
        $this->title       = $days['day'] . '/' . $days['month'] . '/' . $days['year'] . ': ' . $days['title'];
        $this->description = strip_tags(Com::getDescription($days['content']));
        $this->keyword     = $days['title'];
        $comments          = $view->getComments($id);
        $relatedDays       = $view->getRightRelatedDay($day, $month, $year, $location);
        $topDays           = $view->getRightTopDay();
        $this->data        = ["pageTitle" => $this->title, "pageDesc" => $this->description, "imgURL" => $imgURL,
                              "uri"       => $uri, "days" => $days, "id" => $id, "photos" => $photos, "like" => $like,
                              "likeIP"    => $likeIP, "time" => $time, "ipaddress" => $ipaddress,
                              "comments"  => $comments, "relatedDays" => $relatedDays, "topDays" => $topDays];
        $this->addHeaderMetaHTML("<meta property='fb:admins' content='" . FB_PAGEID . "'/>");
        $this->addHeaderMetaHTML("<meta property='og:type' content='article'/>");
        $this->addHeaderMetaHTML("<meta property='og:url' content='{$uri}'/>");
        $this->addHeaderMetaHTML("<meta property='og:title' content='{$this->title}'/>");
        $this->addHeaderMetaHTML("<meta property='og:image' content='{$imgURL}'/>");
        $this->addHeaderMetaHTML("<meta property='og:description' content='{$this->description}'/>");
      }
    }
  }
}