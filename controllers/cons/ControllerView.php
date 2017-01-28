<?php
  namespace cons;

  use core\View;

  class ControllerView extends Controller {
    private $view;

    public function __construct() {
      $this->view = new View();
    }

    public function ajaxShowDay($para) {
      $from = (int)$para['from'];
      $order = $para['order'];
      if ($from > 0) {
        echo($this->view->getBestDays($from, $order));
      }
      exit();
    }

    public function ajaxSearchDay($para) {
      $search = trim($para['search']);
      if ($search != "") {
        echo($this->view->getSearch($search));
      }
      exit();
    }

    public function ajaxSearchMore($para) {
      $search = trim($para['search']);
      $from = (int)$para['from'];
      if ($search != "" && $from > 0) {
        echo($this->view->getSearchMore($search, $from));
      }
      exit();
    }

    public function ajaxShowCalendar($para) {
      $from = (int)$para['from'];
      if ($from > 0) {
        echo($this->view->getCalendar($from));
      }
      exit();
    }

    public function ajaxShowPicture($para) {
      $from = (int)$para['from'];
      if ($from > 0) {
        echo($this->view->getPicture($from));
      }
      exit();
    }

  }