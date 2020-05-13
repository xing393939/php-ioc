<?php
// https://www.jianshu.com/p/acab664daddf

spl_autoload_register();

//实例化容器类
$app = new Core\Container();

$app->bind('Core\Module\Animal', 'Core\Module\Dog');
//$app->bind('Animal', 'Core\Module\Dog');
$people = $app->make(\Core\Module\People::class);

//调用方法
echo $people->findDog();

