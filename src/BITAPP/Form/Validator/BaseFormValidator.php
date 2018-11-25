<?php declare(strict_types=1);

namespace BITAPP\Form\Validator;

use \BITAPP\Form\AbstractDataValueManager;
use \BITAPP\Form\CanBeEmptyTrait;

abstract class BaseFormValidator extends AbstractDataValueManager implements ValidatorInterface
{
    use FormValidatorTrait,
        CanBeEmptyTrait;

    /**
     * @return string
     */
    abstract protected function getDefaultErrorText() :string;
    abstract protected function doCheck();

    /**
     * @param bool $canBeEmpty
     * @param string $customError
     */
    public function __construct(bool $canBeEmpty = false, $customError = '')
    {
        $this->setCustomError($customError);
        $this->setCanBeEmpty($canBeEmpty);
    }

    public function check()
    {
        if (!($this->isCanBeEmpty() && (null === $this->getValue() || $this->getValue() === ''))) {
            $this->doCheck();
        }
    }

    /**
     * @return $this
     */
    public function bindError() :self
    {
        if ($this->customError !== '') {
            $this->setError($this->customError);
        } else {
            $this->setError($this->getDefaultErrorText());
        }
        return $this;
    }
}
