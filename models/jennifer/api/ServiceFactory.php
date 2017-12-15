<?php
namespace jennifer\api;
/**
 * Class ServiceFactory
 * @package api
 */
class ServiceFactory {
  /**
   * Create service
   * @param $serviceClass
   * @param $userData
   * @param $para
   * @return \jennifer\api\ServiceInterface;
   */
  public function createService($serviceClass, $userData, $para) {
    $service = new $serviceClass($userData, $para) or die("Service not found: " . $serviceClass);

    return $service;
  }
}