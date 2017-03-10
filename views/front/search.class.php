<?php
namespace front;
use view\Front;
use core\View;
use sys\System;

class search extends Front {
  protected $title = "Search";
  protected $contentTemplate = "search";

  public function __construct() {
    parent::__construct();

    $tag = System::getViewPara("search");
    if ($tag) {
      $view         = new View();
      $searchResult = $view->getSearch($tag);
      $this->data   = ["searchTerm" => $tag, "searchResult" => $searchResult];
    }
  }
}