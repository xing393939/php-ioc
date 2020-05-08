<?php
namespace Core\Module;

class People
{
    private $dog = null;

    public function __construct(Animal $dog)
    {
        $this->dog = $dog;
    }

    public function findDog()
    {
        return $this->dog->eat();
    }

}