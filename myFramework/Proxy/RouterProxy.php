<?php
namespace MyFramework\Proxy;

class RouterProxy extends \Illuminate\Routing\Router
{
    public function __construct($events, $container) {
        $this->events = $events;
        $this->routes = new \Illuminate\Routing\RouteCollection;
        $this->container = $container;
    }

    public function newRoute($methods, $uri, $action)
    {
        return (new RouteProxy($methods, $uri, $action))
            ->setRouter($this)
            ->setContainer($this->container);
    }

    protected function findRoute($request)
    {
        $this->current = $route = $this->routes->match($request);
        $this->container->instance(RouteProxy::class, $route);
        return $route;
    }

    protected function runRouteWithinStack($route, $request)
    {
        $shouldSkipMiddleware = $this->container->bound('middleware.disable') &&
            $this->container->make('middleware.disable') === true;

        $middleware = $shouldSkipMiddleware ? [] : $this->gatherRouteMiddleware($route);

        return (new PipelineProxy($this->container))
            ->send($request)
            ->through($middleware)
            ->then(function ($request) use ($route) {
                return $this->prepareResponse(
                    $request, $route->run()
                );
            });
    }
}