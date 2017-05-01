<?php
namespace cons;

use core\View;

class ControllerView extends Controller {
  private $view;

  public function __construct() {
    parent::__construct();

    $this->view = new View();
  }

  public function ajaxShowDay($para) {
    $from  = (int)$para['from'];
    $order = $para['order'];
    if ($from > 0) {
      $this->response($this->view->getBestDays($from, $order));
    }
  }

  public function ajaxSearchDay($para) {
    $search = trim($para['search']);
    if ($search != "") {
      $this->response($this->view->getSearch($search));
    }
  }

  public function ajaxSearchMore($para) {
    $search = trim($para['search']);
    $from   = (int)$para['from'];
    if ($search != "" && $from > 0) {
      $this->response($this->view->getSearchMore($search, $from));
    }
  }

  public function ajaxShowCalendar($para) {
    $from = (int)$para['from'];
    if ($from > 0) {
      $this->response($this->view->getCalendar($from));
    }
  }

  public function ajaxShowPicture($para) {
    $from = (int)$para['from'];
    if ($from > 0) {
      $this->response($this->view->getPicture($from));
    }
  }

}