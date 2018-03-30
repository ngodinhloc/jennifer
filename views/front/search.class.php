<?php
namespace front;

use jennifer\view\ViewInterface;
use thedaysoflife\model\User;
use thedaysoflife\view\ViewFront;

class search extends ViewFront implements ViewInterface {
  protected $title = "Search";
  protected $contentTemplate = "search";

  public function __construct() {
    parent::__construct();
    $this->user = new User();
  }

  public function prepare() {
    $search = $this->hasPara("search");
    if ($search) {
      $searchResult = $this->user->getSearch($search);
      $this->data   = ["searchTerm" => $search, "searchResult" => $searchResult];
    }
  }
}