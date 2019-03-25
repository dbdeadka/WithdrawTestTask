<?php declare(strict_types=1);

namespace BITAPP\Controllers;

use BITAPP\Core\Request;
use \BITAPP\Services\Router;
use \BITAPP\Services\Session;
use \BITAPP\Mappers\UserMapper;
use \BITAPP\Services\View;
use \BITAPP\Form\Data\LoginFormData;

class MainController extends BaseController
{
    /**
     * @return string
     * @throws \RuntimeException
     */
    public function renderAuthFormAction() :string
    {
        $templateFromURIData = Request::createArguments();
        return View::template('auth', $templateFromURIData->getParams(), $templateFromURIData->getErrors());
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function loginAction() :string
    {
        $errors = [];

        $data = new LoginFormData(Request::getAllRequest());
        $errors = $data->getErrors();

        $login = $data->getLogin();
        $password = $data->getPassword();

        $route = null;

        if (empty($errors)) {
            $user = UserMapper::loadByLogin($login);
            $error = false;
            $userFieldErrMsg = 'No such user or invalid password';
            if (null === $user) {
                $error = true;
                $errors['login'] = $userFieldErrMsg;
            }

            /** @noinspection NullPointerExceptionInspection */
            if ((!$error) && (!UserMapper::validPassword($user, $password))) {
                $error = true;
                $errors['login'] = $userFieldErrMsg;
            }

            if (!$error) {
                /** @noinspection NullPointerExceptionInspection */
                Session::get()->setUserId($user->getId());
                Session::get()->regenerateId(true);
                $route = Router::ROUT_DASHBOARD;
            }
        }
        $params = [];
        if (!empty($errors)) {
            if ($login) {
                $params['login'] = $login;
            }
            $route = Router::ROUT_MAIN;
        }
        Router::get()->redirect($route, $params, $errors);
        return '';
    }
}
