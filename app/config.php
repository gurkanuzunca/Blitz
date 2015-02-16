<?php

return array(
    'twig' => array(
        'path' => 'app/Views',
        'cache.path' => 'cache',
        'extensions' => array(
            'Library\\Support\\TwigExtension',
            'Twig_Extensions_Extension_Intl'
        )
    ),

    'module' => array(
        'path' => 'app/Modules',
    ),
    'timezone' => 'Europe/Istanbul',

    'database' => array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'symfony',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix'    => ''
    )
);