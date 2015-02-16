<?php

namespace Library\Support;

class Route
{
    public static function module($module, $controller, $action = 'index', $args = array())
    {
        $class = "App\\Modules\\$module\\Controllers\\$controller";

        self::call($class, $action, $args);


    }

    public static function controller($controller, $action = 'index', $args = array())
    {
        $class = "App\\Controllers\\$controller";

        self::call($class, $action, $args);
    }

    public static function install($module)
    {
        $installer = "App\\Modules\\$module\\Installer\\Install";
        new $installer($module);

    }


    private function call($class, $action = 'index', $args = array())
    {
        if (class_exists($class) && method_exists($class, $action)) {
            $controller = new $class;
            call_user_func_array(array($controller, $action), $args);
        } else {
            Blitz::getInstance()->notFound();
        }
    }
} 