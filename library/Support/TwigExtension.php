<?php

namespace Library\Support;

use Twig_Extension;
use Twig_SimpleFunction;


class TwigExtension extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('path', function ($name, $params = array()) {
                return Blitz::getInstance()->urlFor($name, $params);
            }),
            new Twig_SimpleFunction('public', function ($file) {
                return Blitz::getInstance()->request->getRootUri() . '/public/' . $file;
            }),
        );
    }


    public function getName()
    {
        return 'blitzTwig';
    }
} 