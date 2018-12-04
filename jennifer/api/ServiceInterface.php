<?php
namespace jennifer\api;

interface ServiceInterface {
  /**
   * Return map of the service class
   * @return array
   */
  public static function map();

  public function run($action);
}