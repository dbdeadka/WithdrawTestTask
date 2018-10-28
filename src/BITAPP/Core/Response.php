<?php declare(strict_types=1);

namespace BITAPP\Core;

class Response
{
    /**
     * @var array
     */
    private static $error = [];

    /**
     * @param string $field
     * @param string $text
     * @return bool
     */
    public static function setError(string $field, string $text) : bool
    {
        self::$error[$field] = $text;
        return true;
    }

    /**
     * @param string $field
     * @return string|null
     */
    public static function getError(string $field) : ?string
    {
        return self::$error[$field] ?? null;
    }

    /**
     * @param string $field
     * @return bool
     */
    public static function hasError(string $field) : bool
    {
        return isset(self::$error[$field]);
    }

    /**
     * @return bool
     */
    public static function hasAnyError() : bool
    {
        return !empty(self::$error);
    }
}
