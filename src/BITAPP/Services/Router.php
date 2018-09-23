<?php declare(strict_types=1);

namespace BITAPP\Services;

use \BITAPP\AbstractManager;

/**
 * @method static Router get()
 */
class Router extends AbstractManager
{
    protected static $instance;

    private $routers = [];

    const ROUT_MAIN = 'main'; //HUERAGA - may be luchshe enum? ili urlmatcher?
    const ROUT_HANDLEREDIRECT2AUTH  = 'handleRedirect2AuthAction';
    const ROUT_RENDERAUTHFORM  = 'auth';
    const ROUT_LOGIN = 'login';
    const ROUT_DASHBOARD = 'dashboard';
    const ROUT_LOGOUT = 'logout';
    const ROUT_WITHDRAWAL = 'withdrawal';
    const ROUT_RENDER404PAGE = 'render404PageAction';

    public function init()
    {
        $this->setRoute(self::ROUT_MAIN, 'BITAPP\Controllers\MainController::handleMain');
        $this->setRoute(self::ROUT_RENDERAUTHFORM, 'BITAPP\Controllers\MainController::renderAuthFormAction');
        $this->setRoute(self::ROUT_LOGIN, 'BITAPP\Controllers\MainController::loginAction');
        $this->setRoute(self::ROUT_DASHBOARD, 'BITAPP\Controllers\PrivateRoomController::renderAction');
        $this->setRoute(self::ROUT_WITHDRAWAL, 'BITAPP\Controllers\PrivateRoomController::withdrawalAction');
        $this->setRoute(self::ROUT_LOGOUT, 'BITAPP\Controllers\PrivateRoomController::logoutAction');
        $this->setRoute(self::ROUT_RENDER404PAGE, 'BITAPP\Controllers\MainController::render404PageAction');
    }

    /**
     * @param string $route
     * @param string $function
     */
    public function setRoute(string $route, string $function)
    {
        assert(!empty($route));
        assert(!empty($function));
        $this->routers[$route] = $function;
    }

    public static function makeRenderArguments(string $uri, array &$params, array &$errors)
    {
        //HUERAGA - may be beter separate class?
        parse_str(urldecode($uri), $parseResult);
        $params = [];
        $errors = [];
        foreach ($parseResult as $key => $val) {
            if (substr($key, 0, 5) == 'err__') {
                $errors [substr($key, 5)] = $val;
            }
            if (substr($key, 0, 5) == 'par__') {
                $params [substr($key, 5)] = $val;
            }
        }
    }

    public function redirect(string $route, array $fields = [], array $errors = [])
    {
        $parameters = [];
        foreach ($fields as $field => $val) {
            $parameters [] = urlencode('par__' . $field. '=' . $val);
        }
        foreach ($errors as $field => $val) {
            $parameters [] = urlencode('err__' . $field. '=' . $val);
        }
        if (!empty($parameters)) {
            $route .= '?' . implode('&', $parameters);
        }
        header('location: ' . '/' . $route);
    }

    /**
     * @throws \RuntimeException
     * @return string
     */
    public function dispatch() : string
    {
        $route = $_SERVER['REQUEST_URI'];
        $pos = strpos($route, '?');
        if ($pos) {
            $route = substr($route, 0, $pos);
        }

        if ('/' === $route) {
            $route = '/' . self::ROUT_MAIN;
        }

        switch ($route) {
            case '/' . self::ROUT_MAIN:
                return $this->routers[self::ROUT_MAIN];
            case '/' . self::ROUT_RENDERAUTHFORM:
                return $this->routers[self::ROUT_RENDERAUTHFORM];
            case '/' . self::ROUT_LOGIN:
                return $this->routers[self::ROUT_LOGIN];
            case '/' . self::ROUT_WITHDRAWAL:
                return $this->routers[self::ROUT_WITHDRAWAL];
            case '/' . self::ROUT_LOGOUT:
                return $this->routers[self::ROUT_LOGOUT];
            default:
                return $this->routers[self::ROUT_RENDER404PAGE];
        }
    }
}
