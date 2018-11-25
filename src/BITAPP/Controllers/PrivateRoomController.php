<?php declare(strict_types=1);

namespace BITAPP\Controllers;

use \BITAPP\Core\Request;
use \BITAPP\Core\TemplateFromURIData;
use \BITAPP\Mappers\UserMapper;
use \BITAPP\Services\View;
use \BITAPP\Services\Router;
use \BITAPP\Services\Database;
use \BITAPP\Services\Banking;
use \BITAPP\Services\Money;
use \BITAPP\Services\Session;
use \BITAPP\Form\Data\DashboardFormData;

class PrivateRoomController
{
    /**
     * @return string
     * @throws \RuntimeException
     */
    public function renderAction() : string
    {
        $userId = Session::get()->getUserId();
        $user = UserMapper::getUserById($userId, false);
        $templateFromURIData = Request::createArguments();
        $params = $templateFromURIData->getParams();
        $params['balance'] = Money::moneyFormat($user->getBalance());
        return View::template('dashboard', $params, $templateFromURIData->getErrors());
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
            $user = UserMapper::getUserById($user_id, true);

            $data = new DashboardFormData(Request::getAllRequest());
            $errors = $data->getErrors();
            if (isset($errors['amount'])) {
                throw new \InvalidArgumentException('Bad amount');
            }
            $amount = $data->getAmount();

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
        }
        $params = ['balance' => Money::moneyFormat($user->getBalance())];
        Router::get()->redirect(Router::ROUT_DASHBOARD, $params, $errors);
        return '';
    }

    public function logoutAction() : string
    {
        Session::get()->destroy();
        Router::get()->redirect(Router::ROUT_MAIN);
        return '';
    }
}
