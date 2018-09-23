<?php declare(strict_types=1);

namespace BITAPP\Services;

use BITAPP\AbstractManager;
use BITAPP\Mappers\UserMapper;
use BITAPP\Models\User;

/**
 * @method static Banking get()
 */
class Banking extends AbstractManager
{
    protected static $instance;

    /**
     * @param User $user
     * @param int $amount
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return User
     */
    public function withdrawal(User $user, int $amount)
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Invalid $amount = ' . $amount);
        }
        if (is_null($user->getId() || is_null($user->getBalance()))) {
            throw new \InvalidArgumentException('Invalid user');
        }
        UserMapper::changeBalance($user, $amount);
        return $user;
    }
}
