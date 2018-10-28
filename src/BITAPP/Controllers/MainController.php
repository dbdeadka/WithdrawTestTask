<?php declare(strict_types=1);

namespace BITAPP\Controllers;

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
        Router::createArgumentsFromURI($_SERVER['QUERY_STRING'], $params, $errors);
        return View::template('auth', $params, $errors);
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function loginAction() :string
    {
        $errors = [];
        if (Session::get()->isLogged()) {
            Router::get()->redirect(Router::ROUT_DASHBOARD);
        }
        if (!isset($_POST['login'], $_POST['password'])) {
            throw new \RuntimeException('Attemption to hack or frontend error');
        }
        $login = trim($_POST['login']);
        if (empty($login)) {
            $errors['login'] = 'Empty login';
        }
        if (empty($_POST['password'])) {
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
            if ((!$error) && (!UserMapper::validPassword($user, $_POST['password']))) {
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
            if (isset($_POST['login'])) {
                $params['login'] = $_POST['login'];
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
