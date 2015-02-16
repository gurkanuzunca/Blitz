<?php

namespace Library\Support;


abstract class Controller
{

    protected function render($template, array $context = array())
    {
        if (isset(Blitz::getInstance()->environment['slim.flash'])) {
            $context['flash'] = Blitz::getInstance()->environment['slim.flash'];
        }

        echo Blitz::getInstance()->twig->render($template, $context);
    }


} 