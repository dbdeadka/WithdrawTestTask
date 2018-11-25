<?php declare(strict_types=1);

namespace BITAPP\Form\Validator;

class NotBlank extends BaseFormValidator
{
    public function doCheck()
    {
        if (null === $this->getValue() || '' === $this->getValue()) {
            $this->bindError();
        }
        return $this->getValue();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultErrorText() :string
    {
        return 'Field can not be empty';
    }
}
