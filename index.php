<?php

// session_cache_limiter(false);
session_start();

// Composer Autoload.
require_once 'vendor/autoload.php';


// Slim ve Laravel\Eloquent ORM.
use Library\Support\Blitz;
$app = new Blitz();


// Ana rota yüklenir.
require_once 'app/route.php';

foreach ($app->modules as $module) {
    if (isset($module['route'])) {
        require_once $module['route'];
    }
}

$app->notFound(function () use ($app) {
    echo '404';
});

// Slim uygulaması çalıştırılır.
$app->run();
