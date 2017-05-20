<?php
namespace front;
use view\Front;
use thedaysoflife\View;

class search extends Front {
  protected $title = "Search";
  protected $contentTemplate = "search";

  public function __construct() {
    parent::__construct();

    $search = $this->hasPara("search");
    if ($search) {
      $view         = new View();
      $searchResult = $view->getSearch($search);
      $this->data   = ["searchTerm" => $search, "searchResult" => $searchResult];
    }
  }
}