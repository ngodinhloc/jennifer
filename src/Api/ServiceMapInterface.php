<?php

namespace Jennifer\Api;

interface ServiceMapInterface
{
    /**
     * @param string $service
     * @param string $action
     * @return array
     */
    public function map(string $service, string $action);
}