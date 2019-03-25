<?php declare(strict_types=1);

namespace BITAPP\Core;

class Request
{
    /**
     * @var array
     */
    private static $query = [];

    /**
     * @var array
     */
    private static $request = [];


    public static function createFromGlobals() : void
    {
        foreach ($_POST as $key => $val) {
            self::$request[$key] = $val;
        }
        foreach ($_GET as $key => $val) {
            self::$query[$key] = $val;
        }
    }

    public static function createArguments() : TemplateFromURIData
    {
        $params = [];
        $errors = [];
        foreach (self::$query as $key => $val) {
            if (0 === strncmp($key, 'err__', 5)) {
                $errors [(string)substr($key, 5/*5 is the lentgth of 'err__'*/)] = $val;
            }
            if (0 === strncmp($key, 'par__', 5)) {
                $params [(string)substr($key, 5/*5 is the lentgth of 'par__'*/)] = $val;
            }
        }
        $templateFromURIData = new TemplateFromURIData;
        $templateFromURIData->setErrors($errors);
        $templateFromURIData->setParams($params);
        return $templateFromURIData;
    }

    /**
     * @param string $key
     * @param string $default
     * @return string|null
     */
    public static function query(string $key, string $default = null) : ?string
    {
        return self::$query[$key] ?? $default;
    }

    /**
     * @param string $key
     * @param string $default
     * @return string|null
     */
    public static function request(string $key, string $default = null) : ?string
    {
        return self::$request[$key] ?? $default;
    }

    public static function getAllQuery() : array
    {
        return self::$query;
    }

    public static function getAllRequest() : array
    {
        return self::$request;
    }
}
