<?php


namespace MyFramework\Proxy;


class ControllerDispatcherProxy extends \Illuminate\Routing\ControllerDispatcher
{
    public function __construct($container) {
        $this->container = $container;
    }
}