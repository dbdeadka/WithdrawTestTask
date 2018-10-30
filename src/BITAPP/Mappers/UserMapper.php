<?php declare(strict_types=1);

namespace BITAPP\Mappers;

use \BITAPP\Services\Database;
use \BITAPP\Models\User;

class UserMapper extends AbstractMapper
{
    /**
     * {@inheritdoc}
     */
    public static function getTableName() : string
    {
        return 'user';
    }

    /**
     * @param array $dbrow
     * @return User
     */
    public static function createEntityFromDB(array $dbrow) : User
    {
        return (new User())
            ->setId((int)$dbrow['user_id'])
            ->setLogin($dbrow['user_login'])
            ->setPassword($dbrow['user_password'])
            ->setBalance((int)$dbrow['user_balance'])
            ->setPrecision((int)$dbrow['user_precision']);
    }

    public static function validPassword(User $user, string $password) : bool
    {
        return sha1($password) === $user->getPassword();
    }

    /**
     * @param int $id
     * @param bool $lock
     * @return User|null
     * @throws \RuntimeException
     */
    public static function loadById(int $id, bool $lock) : ?User
    {
        $query = '
SELECT 
  `user_id`
  ,`user_login`
  ,`user_password` 
  ,`user_balance`
  ,`user_precision` 
FROM 
  ' . self::getTableName() . ' 
WHERE 
  `user_id` = ' . $id . '
        ';
        if ($lock) {
            $query .= ' FOR UPDATE';
        }
        $result = Database::get()->getResult($query);
        return empty($result) ? null : self::createEntityFromDB($result[0]);
    }

    /**
     * @param int $id
     * @param bool $lock
     * @return User
     * @throws \RuntimeException
     */
    public static function getUserById(int $id, bool $lock) : User
    {
        $user = self::loadById($id, $lock);
        if (null === $user) {
            throw new \RuntimeException('No user with id = ' . $id);
        }
        return $user;
    }

    /**
     * @param int $id
     * @param bool $lock
     * @return User|null
     * @throws \RuntimeException
     */
    public static function findById(int $id, bool $lock) : ?User
    {
        return self::loadById($id, $lock);
    }

    /**
     * @param string $login
     * @return User|null
     * @throws \RuntimeException
     */
    public static function loadByLogin(string $login) : ?User
    {
        $sql = '
SELECT 
  `user_id`
  ,`user_login`
  ,`user_password` 
  ,`user_balance` 
  ,`user_precision` 
FROM 
  ' . self::getTableName() . ' 
WHERE 
  `user_login` = ' . Database::get()->getConnHandler()->quote($login) . '
        ';
        $result = Database::get()->getResult($sql);
        return empty($result) ? null : self::createEntityFromDB($result[0]);
    }

    /**
     * @param User $user
     * @param int $amount
     * @return User
     * @throws \RuntimeException
     */
    public static function changeBalance(User $user, int $amount) : User
    {
        $balance = $user->getBalance() - $amount;
        $query = '
UPDATE 
  `user` 
SET 
  `user_balance` = ' . $balance . '
WHERE
    `user_id` = ' . $user->getId() . '
        ';
        Database::get()->execute($query);
        $user->setBalance($balance);
        return $user;
    }
}
