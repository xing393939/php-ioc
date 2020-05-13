<?php
require __DIR__ . "/../vendor/autoload.php";
$app = new Illuminate\Container\Container();
with(new Illuminate\Events\EventServiceProvider($app))->register();
with(new Illuminate\Routing\RoutingServiceProvider($app))->register();

$manager = new Illuminate\Database\Capsule\Manager();
$manager->addConnection(require __DIR__ . '/../config/database.php');
$manager->bootEloquent();

require __DIR__ . "/../app/Http/routes.php";
$request = Illuminate\Http\Request::createFromGlobals();
$response = $app['router']->dispatch($request);
$response->send();
