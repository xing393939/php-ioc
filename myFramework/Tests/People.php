<?php
namespace MyFramework\Tests;

class People
{
    private $animal = null;

    public function __construct(Animal $dog)
    {
        $this->animal = $dog;
    }

    public function watch()
    {
        return $this->animal->eat();
    }

}