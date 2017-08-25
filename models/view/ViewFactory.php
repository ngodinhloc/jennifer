<?php
namespace view;
/**
 * Class ViewFactory: create view
 * @package view
 */
class ViewFactory {
  /**
   * Create view from class name
   * @param $viewClass
   * @return \view\ViewInterface
   */
  public function createView($viewClass) {
    $view = new $viewClass() or die("View not found: " . $viewClass);

    return $view;
  }
}