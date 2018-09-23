<?php declare(strict_types=1);

namespace BITAPP\Controllers;

use \BITAPP\Mappers\UserMapper;
use \BITAPP\Services\View;
use \BITAPP\Services\Router;
use \BITAPP\Services\Database;
use \BITAPP\Services\Banking;
use \BITAPP\Services\Money;
use \BITAPP\Services\Session;

class PrivateRoomController
{
    /**
     * @return string
     */
    public function renderAction() : string
    {
        $userId = (int)Session::get()->getUserId();
        $user = UserMapper::loadById($userId, false);
        if (is_null($user)) {
            throw new \RuntimeException('No user with id = ' . $userId);
        }
        $params = [];
        $errors = [];
        Router::makeRenderArguments($_SERVER['QUERY_STRING'], $params, $errors);
        $params['balance'] = Money::moneyFormat($user->getBalance());
        return View::template('dashboard', $params, $errors);
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function withdrawalAction() : string
    {
        $user_id = Session::get()->getUserId();
        $errors = [];
        $user = null;

        try {
            Database::get()->beginTransaction();
            $user = UserMapper::loadById($user_id, true);
            if (is_null($user)) {
                throw new \RuntimeException('No such user');
            }
            $amount = (int)$_POST['amount'];
            if ($amount <= 0) {
                $errors['amount'] = 'Sum must be positive integer';
                throw new \InvalidArgumentException('Bad sum');
            }

            if ($user->getBalance() < $amount) {
                Database::get()->rollBack();
                $errors['amount'] = 'Too big amount (no money - no honey)';
                throw new \InvalidArgumentException('Too big amount');
            }
            Banking::get()->withdrawal($user, $amount);
            Database::get()->commit();
        } catch (\Exception $ex) {
            if (Database::get()->inTransaction()) {
                Database::get()->rollBack();
            }
            if (!$user) {
                throw new \RuntimeException('No such user');
            }
        }
        $params = ['balance' => Money::moneyFormat($user->getBalance())];
        Router::get()->redirect(Router::ROUT_MAIN, $params, $errors);
        return '';
    }

    public function logoutAction() : string
    {
        Session::get()->destroy();
        Router::get()->redirect(Router::ROUT_MAIN);
        return '';
    }
}