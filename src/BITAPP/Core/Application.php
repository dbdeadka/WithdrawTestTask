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
    public function process()
    {
        $dispatchResult = Router::get()->dispatch();
        $dispatchResult = Firewall::get()->handle($dispatchResult);
        if (!is_callable([$dispatchResult->getController(), $dispatchResult->getMethod()])) {
            throw new \RuntimeException('Bad controller "' . $controllerClassName
                . '" or method "' . $method . '" passed');
        }
        $controllerClassName = $dispatchResult->getController();
        $methodName = $dispatchResult->getMethod();
        $controller = new $controllerClassName;
        $output = $controller->{$methodName}();
        if (!empty($output)) {
            echo $output;
        }
    }
}
