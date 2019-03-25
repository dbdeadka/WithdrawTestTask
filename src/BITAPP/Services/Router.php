<?php declare(strict_types=1);

namespace BITAPP\Services;

use \BITAPP\AbstractManager;
use BITAPP\Controllers\BaseController;
use BITAPP\Core\ControllerStruct;
use BITAPP\Controllers\MainController;
use BITAPP\Controllers\PrivateRoomController;

/**
 * @method static Router get()
 */
class Router extends AbstractManager
{
    protected static $instance;

    private $routers = [];

    public const ROUT_MAIN = '';
    public const ROUT_LOGIN = 'login';
    public const ROUT_DASHBOARD = 'dashboard';
    public const ROUT_LOGOUT = 'logout';
    public const ROUT_WITHDRAWAL = 'withdrawal';
    public const ROUT_RENDER404PAGE = 'render404PageAction';

    /**
     * @throws \InvalidArgumentException If the provided argument $section is empty
     */
    public function init() : void
    {
        $this->setRoute(
            self::ROUT_MAIN,
            (new ControllerStruct())
                ->setController(MainController::class)
                ->setMethod('renderAuthFormAction')
        );
        $this->setRoute(
            self::ROUT_LOGIN,
            (new ControllerStruct())
                ->setController(MainController::class)
                ->setMethod('loginAction')
        );
        $this->setRoute(
            self::ROUT_DASHBOARD,
            (new ControllerStruct())
                ->setController(PrivateRoomController::class)
                ->setMethod('renderAction')
        );
        $this->setRoute(
            self::ROUT_WITHDRAWAL,
            (new ControllerStruct())
                ->setController(PrivateRoomController::class)
                ->setMethod('withdrawalAction')
        );
        $this->setRoute(
            self::ROUT_LOGOUT,
            (new ControllerStruct())
                ->setController(PrivateRoomController::class)
                ->setMethod('logoutAction')
        );
        $this->setRoute(
            self::ROUT_RENDER404PAGE,
            (new ControllerStruct())
                ->setController(MainController::class)
                ->setMethod('render404PageAction')
        );
    }

    /**
     * @param string $route
     * @param ControllerStruct $controllerStruct
     * @throws \InvalidArgumentException
     */
    public function setRoute(string $route, ControllerStruct $controllerStruct) : void
    {
        $controllerName =  $controllerStruct->getController();
        if ((null === $controllerName) || ('' === $controllerName)) {
            throw new \InvalidArgumentException('Empty controller in routerData.');
        }
        $methodName =  $controllerStruct->getMethod();
        if ((null === $methodName) || ('' === $methodName)) {
            throw new \InvalidArgumentException('Empty method in routerData.');
        }
        $this->routers[$route] = $controllerStruct;
    }

    public function redirect(string $route, array $fields = [], array $errors = []) : void
    {
        $parameters = [];
        foreach ($fields as $field => $val) {
            $parameters [] =  'par__' . urlencode($field) . '=' . urlencode($val);
        }
        foreach ($errors as $field => $val) {
            $parameters [] = 'err__' . urlencode($field) . '=' . urlencode($val);
        }
        if (!empty($parameters)) {
            $route .= '?' . implode('&', $parameters);
        }
        header('location: ' . '/' . $route);
    }

    /**
     * @return ControllerStruct
     * @throws \RuntimeException
     */
    public function dispatch() : ControllerStruct
    {
        $route = $_SERVER['REQUEST_URI']; //HUERAGA - $_SERVER тоже в Request.php?
        $pos = strpos($route, '?');
        if ($pos) {
            $route = substr($route, 0, $pos);
        }
        if ('/' === $route[0]) {
            $route = substr($route, 1);
        }
        if (empty($route)) {
            $route = self::ROUT_MAIN;
        }
        $result = null;
        switch ($route) {
            case self::ROUT_MAIN:
                $result = $this->routers[self::ROUT_MAIN];
                break;
            case self::ROUT_LOGIN:
                $result = $this->routers[self::ROUT_LOGIN];
                break;
            case self::ROUT_DASHBOARD:
                $result = $this->routers[self::ROUT_DASHBOARD];
                break;
            case self::ROUT_WITHDRAWAL:
                $result = $this->routers[self::ROUT_WITHDRAWAL];
                break;
            case self::ROUT_LOGOUT:
                $result = $this->routers[self::ROUT_LOGOUT];
                break;
            default:
                $result = $this->routers[self::ROUT_RENDER404PAGE];
                break;
        }
        return $result;
    }
}
