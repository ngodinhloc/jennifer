<?php
  namespace front;
  use view\Front;
  use com\Com;
  use html\jobject\FlexSlider;
  use sys\System;
  use thedaysoflife\View;

  class day extends Front {
    protected $contentTemplate = "day";

    public function __construct() {
      parent::__construct();

      $view = new View();
      $topDays = $view->getRightTopDayHTML();
      $this->data = ["pageTitle" => $this->title, "pageDesc" => $this->description, "topDays" => $topDays,];
      $id = $this->hasPara("day");
      if ($id) {
        $days = $view->getDayById($id);
        if ($days) {
          $likeIP = explode('|', $days['like_ip']);
          $time = Com::getTimeDiff($days['time']);
          $ipaddress = System::getTodayIPaddress();
          $day = (int)$days['day'];
          $month = (int)$days['month'];
          $year = (int)$days['year'];
          $location = $days['location'];
          $uri = Com::getDayLink($days);
          $photos = trim($days['photos']);
          $imgURL = "";
          $slider = "";
          if ($photos != "") {
            $photoArray = explode(',', $photos);
            $imgURL = Com::getPhotoURL($photoArray[0], PHOTO_FULL_NAME);
            $fullPhotos = Com::getPhotoArray($photoArray, PHOTO_FULL_NAME);
            $thumbPhotos = Com::getPhotoArray($photoArray, PHOTO_THUMB_NAME);
            $flexSlider = new FlexSlider([], ["fullPhotos"  => $fullPhotos,
                                              "thumbPhotos" => $thumbPhotos]);
            $slider = $flexSlider->render();
            $this->registerMetaFiles($flexSlider);
          }
          $this->title = Com::getDayTitle($days);
          $this->description = Com::getDayDescription($days);
          $this->keyword = $days['title'];
          $comments = $view->getComments($id);
          $relatedDays = $view->getRightRelatedDayHTML($day, $month, $year, $location);
          $this->data = ["uri"         => $uri,
                         "days"        => $days,
                         "slider"      => $slider,
                         "likeIP"      => $likeIP,
                         "time"        => $time,
                         "ipaddress"   => $ipaddress,
                         "comments"    => $comments,
                         "relatedDays" => $relatedDays != "" ? $relatedDays : "No related days found",
                         "topDays"     => $topDays,];
          $this->addMetaTag("<meta property='fb:admins' content='" . FB_PAGEID . "'/>");
          $this->addMetaTag("<meta property='og:type' content='article'/>");
          $this->addMetaTag("<meta property='og:url' content='{$uri}'/>");
          $this->addMetaTag("<meta property='og:title' content='{$this->title}'/>");
          $this->addMetaTag("<meta property='og:image' content='{$imgURL}'/>");
          $this->addMetaTag("<meta property='og:description' content='{$this->description}'/>");
          $this->addMetaFile(SITE_URL . "/plugins/jquery/jquery.autosize.min.js");
        }
      }
    }
  }