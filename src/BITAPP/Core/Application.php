<?php declare(strict_types=1);

namespace BITAPP\Core;

use \BITAPP\Services\Config;
use BITAPP\Services\Firewall;
use \BITAPP\Services\Router;
use \BITAPP\Services\Session;

class Application
{
    /**
     * @throws \Exception
     */
    public function __construct()
    {
        require_once __DIR__ . '/defines.php';
        Config::get()->load(
            APP_DIR . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'config.' . APP_BUILD_TYPE . '.php'
        );
        Session::get()->open();
        Session::get()->close();
    }

    /**
     * @throws \RuntimeException
     */
    public function process() : void
    {
        $dispatchResult = Router::get()->dispatch();
        $firewallResult = Firewall::get()->handle($dispatchResult);
        $output = '';
        if (null !== $firewallResult->getRedirectUri()) {
            Router::get()->redirect($firewallResult->getRedirectUri());
        } else {
            $controllerClassName = $firewallResult->getController();
            $methodName = $firewallResult->getMethod();
            if (!\is_callable([$firewallResult->getController(), $firewallResult->getMethod()])) {
                throw new \RuntimeException('Bad controller "' . $controllerClassName
                    . '" or method "' . $methodName . '" passed');
            }
            $controller = new $controllerClassName;
            Request::createFromGlobals();
            $output = $controller->{$methodName}();
        }
        if ('' !== $output) {
            echo $output;
        }
    }
}
