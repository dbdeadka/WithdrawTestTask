<?php declare(strict_types=1);

namespace BITAPP;

abstract class AbstractManager
{
    protected static $instance;

    private function __construct()
    {
        $this->init();
    }


    public static function get()
    {
        if (is_null(static::$instance)) {
            $class = get_called_class();
            static::$instance = new $class();
        }
        return static::$instance;
    }

    protected function init()
    {
    }
}
