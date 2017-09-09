<?php
namespace front;

use thedaysoflife\model\User;
use thedaysoflife\view\ViewFront;
use view\ViewInterface;

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
      $search       = $this->user->escapeString($search);
      $searchResult = $this->user->getSearch($search);
      $this->data   = ["searchTerm" => $search, "searchResult" => $searchResult];
    }
  }
}