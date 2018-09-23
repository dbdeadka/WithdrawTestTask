<?php declare(strict_types=1);

namespace BITAPP\Services;

use BITAPP\AbstractManager;

/**
 * @method static Session get()
 */
class Session extends AbstractManager
{
    protected static $instance;

    protected $isOpened = false;

    const KEY_USER_ID = 'user_id';

    public function open()
    {
        session_start(['cookie_httponly' => true, 'use_strict_mode' => true]);
        $this->isOpened = true;
    }

    public function close()
    {
        session_write_close();
        $this->isOpened = false;
    }

    public function setUserId(int $userId)
    {
        $wasOpen = $this->isOpened;
        if (!$wasOpen) {
            $this->open();
        }
        $_SESSION[self::KEY_USER_ID] = $userId;
        if (!$wasOpen) {
            $this->close();
        }
    }

    public function getUserId() : ?int
    {
        return isset($_SESSION[self::KEY_USER_ID]) ? $_SESSION[self::KEY_USER_ID] : null;
    }

    public function isLogged(): bool
    {
        return isset($_SESSION[self::KEY_USER_ID]);
    }

    public function destroy()
    {
        if (!$this->isOpened) {
            $this->open();
        }
        session_destroy();
        unset($_SESSION);
        if ($this->isOpened) {
            $this->close();
        }
    }
}
