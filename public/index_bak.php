<?php
// https://www.jianshu.com/p/acab664daddf
require __DIR__ . "/../vendor/autoload.php";

//example 1
$app = new \MyFramework\Container();
$app->bind('cat', 'MyFramework\Tests\Cat');
$cat = $app->make("cat");
echo $cat->eat();

//example 2
$app = new \MyFramework\Container();
$app->bind('MyFramework\Tests\Animal', 'MyFramework\Tests\Cat');
$people = $app->make(\MyFramework\Tests\People::class);
echo $people->watch();
