<?php
namespace jennifer\view;
/**
 * Class ViewFactory: create view
 * @package jennifer\view
 */
class ViewFactory {
  /**
   * Create view from class name
   * @param $viewClass
   * @return \jennifer\view\ViewInterface
   */
  public function createView($viewClass) {
    $view = new $viewClass() or die("View not found: " . $viewClass);

    return $view;
  }
}