<?php
require __DIR__ . "/../vendor/autoload.php";
$app = new MyFramework\Container();
with(new Illuminate\Routing\RoutingServiceProvider($app))->register();
$app->singleton('events', function ($app) {
    return (new MyFramework\Proxy\DispatcherProxy($app))->setQueueResolver(function () use ($app) {
        return $app->make(Illuminate\Contracts\Queue\Factory::class);
    });
});
$app->singleton('router', function ($app) {
    return new MyFramework\Proxy\RouterProxy($app['events'], $app);
});

$manager = new Illuminate\Database\Capsule\Manager();
$manager->addConnection(require __DIR__ . '/../config/database.php');
$manager->bootEloquent();

require __DIR__ . "/../app/Http/routes.php";
$request = Illuminate\Http\Request::createFromGlobals();
$response = $app['router']->dispatch($request);
$response->send();
