<?php

use Library\Support\Route;


$app->get('/', function() {
    Route::module('Blog', 'BlogController');
})->name('blog');
