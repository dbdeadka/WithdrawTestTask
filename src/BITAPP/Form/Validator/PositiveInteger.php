<?php declare(strict_types=1);

namespace BITAPP\Form\Validator;

class PositiveInteger extends BaseFormValidator
{
    public function doCheck()
    {
        if (!( (int)$this->getValue() == $this->getValue() && (int)$this->getValue() > 0 )) {
            $this->bindError();
        }
    }

    /**
     * @return string
     */
    protected function getDefaultErrorText() :string
    {
        return 'Field can be positive integer only';
    }
}
