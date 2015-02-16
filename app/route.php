<?php

use Library\Support\Route;

// Kurulum işlemleri bittiğinde rota kaldırılmalı.
$app->get('/install/:module', function($module) {
    Route::install($module);
})->name('install');






