<?php


namespace MyFramework\Proxy;


class PipelineProxy extends \Illuminate\Routing\Pipeline
{
    public function __construct($container) {
        $this->container = $container;
    }
}