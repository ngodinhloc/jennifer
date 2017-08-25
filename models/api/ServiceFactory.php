<?php
namespace api;
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
   * @return \api\ServiceInterface;
   */
  public function createService($serviceClass, $userData, $para) {
    $service = new $serviceClass($userData, $para) or die("Service not found: " . $serviceClass);

    return $service;
  }
}