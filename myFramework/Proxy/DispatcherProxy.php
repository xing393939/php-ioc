<?php


namespace MyFramework\Proxy;


class DispatcherProxy extends \Illuminate\Events\Dispatcher
{
    public function __construct($container) {
        $this->container = $container;
    }
}