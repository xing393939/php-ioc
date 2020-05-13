<?php
namespace MyFramework\Tests;

Class Food
{
    private $name = '骨头';

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}