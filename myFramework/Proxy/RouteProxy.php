<?php


namespace MyFramework\Proxy;


class RouteProxy extends \Illuminate\Routing\Route
{
    public function setContainer($container)
    {
        $this->container = $container;

        return $this;
    }

    public function controllerDispatcher()
    {
        return new ControllerDispatcherProxy($this->container);
    }
}