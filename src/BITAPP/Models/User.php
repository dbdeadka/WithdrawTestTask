<?php declare(strict_types=1);

namespace BITAPP\Models;

class User extends AbstractModel
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var string $login
     */
    protected $login;

    /**
     * @var string $password
     */
    protected $password;

    /**
     * @var int $balance
     */
    protected $balance;

    /**
     * @var int $precision
     */
    protected $precision;


    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setBalance(int $balance): self
    {
        $this->balance = $balance;
        return $this;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setPrecision(int $precision): self
    {
        $this->precision = $precision;
        return $this;
    }

    public function getPrecision(): ?int
    {
        return $this->precision;
    }
}
