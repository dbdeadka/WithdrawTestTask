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
        if (empty($dispatchResult)) {
            throw new \RuntimeException('Bad dispatch "' . serialize($_SERVER));
        }
        $controllerClassName = '';
        $method = '';
        if (!Firewall::get()->handle($dispatchResult, $controllerClassName, $method)) {
            throw new \RuntimeException('Firewall did not pass "' . $dispatchResult . '"');
        }
        if (!is_callable([$controllerClassName, $method])) {
            throw new \RuntimeException('Bad controller "' . $controllerClassName
                . '" or method "' . $method . '" passed');
        }

        $controller = new $controllerClassName;
        $output = $controller->{$method}();
        if (!empty($output)) {
            echo $output;
        }
    }
}
