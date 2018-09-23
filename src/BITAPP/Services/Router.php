<?php declare(strict_types=1);

namespace BITAPP\Services;

use \BITAPP\AbstractManager;
use BITAPP\Core\RouteData;

/**
 * @method static Router get()
 */
class Router extends AbstractManager
{
    protected static $instance;

    private $routers = [];

    const ROUT_MAIN = 'main';
    const ROUT_LOGIN = 'login';
    const ROUT_DASHBOARD = 'dashboard';
    const ROUT_LOGOUT = 'logout';
    const ROUT_WITHDRAWAL = 'withdrawal';
    const ROUT_RENDER404PAGE = 'render404PageAction';

    public function init()
    {
        $this->setRoute(
            self::ROUT_MAIN,
            (new RouteData())
                ->setController('BITAPP\Controllers\MainController')
                ->setMethod('renderAuthFormAction')
        );
        $this->setRoute(
            self::ROUT_LOGIN,
            (new RouteData())
                ->setController('BITAPP\Controllers\MainController')
                ->setMethod('loginAction')
        );
        $this->setRoute(
            self::ROUT_DASHBOARD,
            (new RouteData())
                ->setController('BITAPP\Controllers\PrivateRoomController')
                ->setMethod('renderAction')
        );
        $this->setRoute(
            self::ROUT_WITHDRAWAL,
            (new RouteData())
                ->setController('BITAPP\Controllers\PrivateRoomController')
                ->setMethod('withdrawalAction')
        );
        $this->setRoute(
            self::ROUT_LOGOUT,
            (new RouteData())
                ->setController('BITAPP\Controllers\PrivateRoomController')
                ->setMethod('logoutAction')
        );
        $this->setRoute(
            self::ROUT_RENDER404PAGE,
            (new RouteData())
                ->setController('BITAPP\Controllers\MainController')
                ->setMethod('render404PageAction')
        );
    }

    /**
     * @param string $route
     * @param RouteData $routeData
     * @throws \InvalidArgumentException
     */
    public function setRoute(string $route, RouteData $routeData)
    {
        if (empty($route)) {
            throw new \InvalidArgumentException('Empty route.');
        }
        if (is_null($routeData->getController()) || empty($routeData->getController())) {
            throw new \InvalidArgumentException('Empty controller in routerData.');
        }
        if (is_null($routeData->getMethod()) || empty($routeData->getMethod())) {
            throw new \InvalidArgumentException('Empty method in routerData.');
        }
        $this->routers[$route] = $routeData;
    }

    public static function createArgumentsFromURI(string $uri, array &$params, array &$errors)
    {
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
     * @return RouteData
     */
    public function dispatch() : RouteData
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
            case '/' . self::ROUT_LOGIN:
                return $this->routers[self::ROUT_LOGIN];
            case '/' . self::ROUT_DASHBOARD:
                return $this->routers[self::ROUT_DASHBOARD];
            case '/' . self::ROUT_WITHDRAWAL:
                return $this->routers[self::ROUT_WITHDRAWAL];
            case '/' . self::ROUT_LOGOUT:
                return $this->routers[self::ROUT_LOGOUT];
            default:
                return $this->routers[self::ROUT_RENDER404PAGE];
        }
    }
}
