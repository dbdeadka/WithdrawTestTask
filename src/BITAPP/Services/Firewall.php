<?php declare(strict_types=1);

namespace BITAPP\Services;

use \BITAPP\AbstractManager;
use BITAPP\Core\RouteData;

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

    public function init()
    {
        $this->zones = [];
        $this->zones[self::ZONE_ANON] = [];
        $this->zones[self::ZONE_AUTH] = [];
        $this->zones[self::ZONE_ANON][self::CONTROLLER_MAIN] = true;
        $this->zones[self::ZONE_AUTH][self::CONTROLLER_PRIVATEROOM] = true;
    }

    /**
     * @param RouteData $routeData
     * @throws \RuntimeException
     * @return RouteData
     */
    public function handle(RouteData $routeData) : RouteData
    {
        if ((!isset($this->zones[self::ZONE_ANON][$routeData->getController()]))
                &&
            (!isset($this->zones[self::ZONE_AUTH][$routeData->getController()]))) {
            throw new \RuntimeException('Invalid controller name (no such zone): ' . $routeData->getController());
        }

        if ((self::CONTROLLER_MAIN == $routeData->getController())
            && ('renderAuthFormAction' == $routeData->getMethod())) {
            if (Session::get()->isLogged()) {
                $routeData->setController(self::CONTROLLER_PRIVATEROOM);
                $routeData->setMethod('renderAction');
            }
            return $routeData;
        }

        if (Session::get()->isLogged()) {
            if (isset($this->zones[self::ZONE_ANON][$routeData->getController()])) {
                throw new \RuntimeException('Invalid zone');
            }
        } else {
            if (isset($this->zones[self::ZONE_AUTH][$routeData->getController()])) {
                throw new \RuntimeException('Invalid zone');
            }
        }
        return $routeData;
    }
}
