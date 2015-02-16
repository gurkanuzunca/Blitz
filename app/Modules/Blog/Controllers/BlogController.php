<?php

namespace App\Modules\Blog\Controllers;


use Library\Support\Controller;

class BlogController extends Controller
{
    public function index()
    {
        $this->render('@Blog/index.twig');
    }
} 