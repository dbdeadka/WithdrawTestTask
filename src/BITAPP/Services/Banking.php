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
     * @return User
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function withdrawal(User $user, int $amount) : User
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Invalid $amount = ' . $amount);
        }
        if ((null === $user->getId()) || (null === $user->getBalance())) {
            throw new \InvalidArgumentException('Invalid user');
        }
        return UserMapper::changeBalance($user, $amount);
    }
}
