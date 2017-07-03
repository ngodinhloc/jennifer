<?php
namespace front;
use view\Front;
use thedaysoflife\User;

class search extends Front {
  protected $title = "Search";
  protected $contentTemplate = "search";

  public function __construct() {
    parent::__construct();

    $search = $this->hasPara("search");
    if ($search) {
      $user         = new User();
      $searchResult = $user->getSearch($search);
      $this->data   = ["searchTerm" => $search, "searchResult" => $searchResult];
    }
  }
}