<?php declare(strict_types=1);

namespace BITAPP\Services;

use \BITAPP\AbstractManager;

/**
 * @method static Firewall get()
 */
class Firewall extends AbstractManager
{
    protected static $instance;

    const ZONE_ANON = 'anon';
    const ZONE_AUTH = 'auth';

    protected $zones;

    const CONTROLLER_MAIN = 'BITAPP\Controllers\MainController';
    const CONTROLLER_PRIVATEROOM = 'BITAPP\Controllers\PrivateRoomController';

    const ROUT_RENDER404PAGE = 'render404PageAction';

    public function init()
    {
        $this->zones = [];
        $this->zones[self::ZONE_ANON] = [];
        $this->zones[self::ZONE_AUTH] = [];
        $this->zones[self::ZONE_ANON][self::CONTROLLER_MAIN] = true;
        $this->zones[self::ZONE_AUTH][self::CONTROLLER_PRIVATEROOM] = true;
    }

    /**
     * @param string $path
     * @param string $controllerClassName
     * @param string $methodName
     * @throws \RuntimeException
     * @return bool
     */
    public function handle(string $path, string& $controllerClassName, string& $methodName) : bool
    {
        list($controllerClassName, $methodName) = explode('::', $path);
        assert(
            isset($this->zones[self::ZONE_ANON][$controllerClassName])
                ||
            isset($this->zones[self::ZONE_AUTH][$controllerClassName])
        );

        if ((self::CONTROLLER_MAIN == $controllerClassName) && ('handleMain' == $methodName)) {
            if (Session::get()->isLogged()) {
                $controllerClassName = 'BITAPP\Controllers\PrivateRoomController';
                $methodName = 'renderAction';
            } else {
                $controllerClassName = 'BITAPP\Controllers\MainController';
                $methodName = 'renderAuthFormAction';
            }
            return true;
        }

        if (Session::get()->isLogged()) {
            if (isset($this->zones[self::ZONE_ANON][$controllerClassName])) {
                return false;
            }
        } else {
            if (isset($this->zones[self::ZONE_AUTH][$controllerClassName])) {
                return false;
            }
        }
        return true;
    }
}
