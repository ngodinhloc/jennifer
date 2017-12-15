<?php
namespace jennifer\api;

use thedaysoflife\service\DayService;

/**
 * Class ServiceMap
 * @package api
 */
class ServiceMap {
  /** @var array list of all registered service maps */
  protected $maps = [];

  public function __construct() {
    /** All api services need to be registered in ServiceMap so that API could find them */
    $this->register(DayService::map());
  }

  /**
   * Map request service and action to service class and method
   * @param $service
   * @param $action
   * @return array
   */
  public function map($service, $action) {
    if (isset($this->maps[$service]["actions"][$action])) {
      $class  = $this->maps[$service]["class"];
      $method = $this->maps[$service]["actions"][$action];

      return [$class, $method];
    }

    return [false, false];
  }

  /**
   * Register service to service map
   * @param array $map
   */
  protected function register($map = []) {
    if (isset($map["service"])) {
      $service = $map["service"];
      if (!isset($this->maps[$service])) {
        $this->maps[$service] = $map;
      }
    }
  }
}