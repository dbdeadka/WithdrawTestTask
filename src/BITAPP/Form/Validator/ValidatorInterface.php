<?php declare(strict_types=1);

namespace BITAPP\Form\Validator;

interface ValidatorInterface
{
    public function isValid(): bool;
}
