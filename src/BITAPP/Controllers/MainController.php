<?php declare(strict_types=1);

namespace BITAPP\Controllers;

use BITAPP\Core\Request;
use \BITAPP\Services\Router;
use \BITAPP\Services\Session;
use \BITAPP\Mappers\UserMapper;
use \BITAPP\Services\View;

class MainController
{
    /**
     * @return string
     * @throws \RuntimeException
     */
    public function renderAuthFormAction() :string
    {
        $params = [];
        $errors = [];
        Request::createArguments($params, $errors);
        return View::template('auth', $params, $errors);
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function loginAction() :string
    {
        $errors = [];

        if ((null === Request::request('login')) || (null === Request::request('password'))) {
            throw new \RuntimeException('Attemption to hack or frontend error (no necessary post values)');
        }
        $login = trim(Request::request('login'));
        if ('' === $login) {
            $errors['login'] = 'Empty login';
        }
        if (null === Request::request('password')) {
            $errors['password'] = 'Empty password';
        }

        $route = null;

        if (empty($errors)) {
            $user = UserMapper::loadByLogin($login);
            $error = false;
            if (null === $user) {
                $error = true;
                $errors['login'] = 'No such user or invalid password';
            }

            /** @noinspection NullPointerExceptionInspection */
            if ((!$error) && (!UserMapper::validPassword($user, Request::request('password')))) {
                $error = true;
                $errors['login'] = 'No such user or invalid password';
            }

            if (!$error) {
                /** @noinspection NullPointerExceptionInspection */
                Session::get()->setUserId($user->getId());
                Session::get()->regenerateId(true);
                $route = Router::ROUT_DASHBOARD;
            }
        }
        $params = [];
        if (null === $route) {
            if (null !== Request::request('login')) {
                $params['login'] = Request::request('login');
            }
            $route = Router::ROUT_MAIN;
        }
        Router::get()->redirect($route, $params, $errors);
        return '';
    }

    /**
     * @return string
     */
    public function render404PageAction() :string
    {
        header('HTTP/1.0 404 Not Found');
        return '';
    }
}
