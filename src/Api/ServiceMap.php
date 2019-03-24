<?php

namespace Jennifer\Api;

/**
 * Class ServiceMap
 * @package Jennifer\Api
 */
abstract class ServiceMap implements ServiceMapInterface
{
    /** @var array list of all registered service maps */
    protected $maps = [];

    /*
     * All api services need to be registered in ServiceMap so that API could find them
     * $this->register(DayService::map());
     */

    /**
     * Map request service and action to service class and method
     * @param string $service
     * @param string $action
     * @return array
     */
    public function map(string $service, string $action)
    {
        if (isset($this->maps[$service]["actions"][$action])) {
            $class = $this->maps[$service]["class"];
            $method = $this->maps[$service]["actions"][$action];

            return [$class, $method];
        }

        return [false, false];
    }

    /**
     * Register service to service map
     * @param array $map
     */
    protected function register($map = [])
    {
        if (isset($map["service"])) {
            $service = $map["service"];
            if (!isset($this->maps[$service])) {
                $this->maps[$service] = $map;
            }
        }
    }
}