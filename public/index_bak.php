<?php
// https://www.jianshu.com/p/acab664daddf
require __DIR__ . "/../vendor/autoload.php";

//example证明：对象在函数中是引用传递
class classA {
    function a1 () {
        $app = new \MyFramework\Container();
        $food = $app->make(\MyFramework\Tests\Food::class);
        $this->a2($food, '大米');
        echo $food->getName();
    }

    function a2($food, $name) {
        $food->setName($name);
    }
}
$app = new \MyFramework\Container();
$a = $app->make(classA::class);
$a->a1();

//example 1
$app = new \MyFramework\Container();
$app->bind('cat', 'MyFramework\Tests\Cat');
$cat = $app->make("cat");
echo $cat->eat();

//example 2
$app = new \MyFramework\Container();
$app->bind('MyFramework\Tests\Animal', 'MyFramework\Tests\Dog');
$people = $app->make(\MyFramework\Tests\People::class);
echo $people->watch();
