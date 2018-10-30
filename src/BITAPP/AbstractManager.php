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
        if (null === static::$instance) {
            $class = static::class;
            static::$instance = new $class();
        }
        return static::$instance;
    }

    protected function init() : void
    {
    }
}
