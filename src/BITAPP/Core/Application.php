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
        $controllerMethodPair = Router::get()->dispatch();
        $firewallControllerMethodPair = Firewall::get()->handle($controllerMethodPair);
        $output = '';
        if (null !== $firewallControllerMethodPair->getRedirectUri()) {
            Router::get()->redirect($firewallControllerMethodPair->getRedirectUri());
        } else {
            $controllerClassName = $firewallControllerMethodPair->getController();
            $methodName = $firewallControllerMethodPair->getMethod();
            if (!\is_callable([$controllerClassName, $methodName])) {
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
