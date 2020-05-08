<?php
namespace Core\Module;

class Dog implements Animal
{
    private $food = null;

    public function __construct(Food $food)
    {
        $this->food = $food;
    }

    public function eat()
    {
        return '吃：' . $this->food->name;
    }
}
