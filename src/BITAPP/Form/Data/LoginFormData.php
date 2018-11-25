<?php declare(strict_types=1);

namespace BITAPP\Form\Data;

use \BITAPP\Form\AbstractFormData;
use \BITAPP\Form\RuleContainer;
use \BITAPP\Form\Validator\NotBlank;
use \BITAPP\Form\Converter\Trim;

class LoginFormData extends AbstractFormData
{
    protected $login;
    protected $password;

    /** {@inheritdoc} */
    protected function getRules(): array
    {
        return [
            'login' => new RuleContainer([new Trim(), new NotBlank()]),
            'password' => new RuleContainer([new NotBlank()])
        ];
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
