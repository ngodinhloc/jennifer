<?php
namespace api;
use api\service\DayService;

class ServiceMap {
  protected $maps = [];

  public function __construct() {
    /**
     *  All model in api\service need to be registered in ServiceMap so that API could find them
     */
    $this->register(DayService::map());
  }

  /**
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
    $service = $map["service"];
    if (!isset($this->maps[$service])) {
      $this->maps[$service] = $map;
    }
  }
}