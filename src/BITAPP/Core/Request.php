<?php declare(strict_types=1);

namespace BITAPP\Core;

class Request
{
    /**
     * @var array
     */
    private static $get = [];

    /**
     * @var array
     */
    private static $post = [];


    public static function createFromGlobals() : void
    {
        foreach ($_POST as $key => $val) {
            self::$post[$key] = $val;
        }
        //HUERAGA - правильная ли обработка? то, что содержимое QUERY_STRING загоняю в get?
        parse_str(urldecode($_SERVER['QUERY_STRING']), $parseResult);
        foreach (/**@var array $parseResult*/$parseResult as $key => $val) {
            self::$get[$key] = $val;
        }
    }

    public static function createArguments(array &$params, array &$errors) : void
    {
        $params = [];
        $errors = [];
        foreach (self::$get as $key => $val) {
            if (0 === strncmp($key, 'err__', 5)) {
                $errors [(string)substr($key, 5/*5 is the lentgth of 'err__'*/)] = $val;
            }
            if (0 === strncmp($key, 'par__', 5)) {
                $params [(string)substr($key, 5/*5 is the lentgth of 'par__'*/)] = $val;
            }
        }
    }

    /**
     * @param string $key
     * @param string $default
     * @return string|null
     */
    public static function query(string $key, string $default = null) : ?string
    {
        return self::$get[$key] ?? $default;
    }

    /**
     * @param string $key
     * @param string $default
     * @return string|null
     */
    public static function request(string $key, string $default = null) : ?string
    {
        return self::$post[$key] ?? $default;
    }
}
