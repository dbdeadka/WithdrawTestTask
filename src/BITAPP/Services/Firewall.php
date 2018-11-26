<?php declare(strict_types=1);

namespace BITAPP\Services;

use \BITAPP\AbstractManager;
use BITAPP\Core\ControllerStruct;
use BITAPP\Controllers\PrivateRoomController;
use BITAPP\Controllers\MainController;
use BITAPP\Core\FirewallStruct;

/**
 * @method static Firewall get()
 */
class Firewall extends AbstractManager
{
    protected static $instance;

    public const ZONE_ANON = 'anon';
    public const ZONE_AUTH = 'auth';

    public const CONTROLLER_MAIN = MainController::class;
    public const CONTROLLER_PRIVATEROOM = PrivateRoomController::class;

    public const RENDER_AUTH_FORM_ACTION = 'renderAuthFormAction';

    protected $zones;

    public $redirectCases;

    public function init() : void
    {
        $this->zones = [];
        $this->zones[self::ZONE_ANON] = [];
        $this->zones[self::ZONE_AUTH] = [];
        $this->zones[self::ZONE_ANON][self::CONTROLLER_MAIN] = true;
        $this->zones[self::ZONE_AUTH][self::CONTROLLER_PRIVATEROOM] = true;

        $this->redirectCases = [];
        $this->redirectCases [self::CONTROLLER_MAIN . self::RENDER_AUTH_FORM_ACTION . (int)true/*IsLogged*/]
            = Router::ROUT_DASHBOARD;
    }

    /**
     * @param ControllerStruct $controllerStruct
     * @return FirewallStruct
     * @throws \RuntimeException
     */
    public function handle(ControllerStruct $controllerStruct) : FirewallStruct
    {
        $firewallStruct = new FirewallStruct;
        if (!isset($this->zones[self::ZONE_ANON][$controllerStruct->getController()])
            && !isset($this->zones[self::ZONE_AUTH][$controllerStruct->getController()])
        ) {
            throw new \RuntimeException(
                'Invalid controller name (no such zone): ' . $controllerStruct->getController()
            );
        }

        $currentCase = $controllerStruct->getController() . $controllerStruct->getMethod()
            . (int)Session::get()->isLogged();

        if (array_key_exists($currentCase, $this->redirectCases)) {
            $firewallStruct->setRedirectUri($this->redirectCases[$currentCase]);
        } else {
            if (Session::get()->isLogged()) {
                if (isset($this->zones[self::ZONE_ANON][$controllerStruct->getController()])) {
                    throw new \RuntimeException('Invalid zone');
                }
            } else {
                if (isset($this->zones[self::ZONE_AUTH][$controllerStruct->getController()])) {
                    throw new \RuntimeException('Invalid zone');
                }
            }
            $firewallStruct->setMethod($controllerStruct->getMethod());
            $firewallStruct->setController($controllerStruct->getController());
        }
        return $firewallStruct;
    }
}
