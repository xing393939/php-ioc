<?php


namespace App\Http\Controllers;

use App\Models\Student;

class WelcomeController
{
    public function index()
    {
        $line = Student::first();
        return $line;
    }

}