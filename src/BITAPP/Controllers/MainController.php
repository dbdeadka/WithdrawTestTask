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
     */
    public function renderAuthFormAction() :string
    {
        $params = [];
        $errors = [];
        Router::createArgumentsFromURI($_SERVER['QUERY_STRING'], $params, $errors);
        return View::template('auth', $params, $errors);
    }

    public function loginAction() :string
    {
        $errors = [];
        if (Session::get()->isLogged()) {
            Router::get()->redirect(Router::ROUT_DASHBOARD);
        }
        if (!isset($_POST['login']) || !isset($_POST['password'])) {
            throw new \RuntimeException('Attemption to hack or frontend error');
        }
        $login = trim($_POST['login']);
        if (empty($login)) {
            $errors['login'] = 'Empty login';
        }
        if (empty($_POST['password'])) {
            $errors['password'] = 'Empty password';
        }

        if (empty($errors)) {
            $user = UserMapper::loadByLogin($login);
            $error = false;
            if (is_null($user)) {
                $error = true;
                $errors['login'] = 'No such user or invalid password';
            }
            if (!$error) {
                if (!UserMapper::validPassword($user, $_POST['password'])) {
                    $error = true;
                    $errors['login'] = 'No such user or invalid password';
                }
            }
            if (!$error) {
                Session::get()->setUserId($user->getId());
                Router::get()->redirect(Router::ROUT_DASHBOARD);
                return '';
            }
        }
        $params = [];
        if (isset($_POST['login'])) {
            $params['login'] = $_POST['login'];
        }
        Router::get()->redirect(Router::ROUT_MAIN, $params, $errors);
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
