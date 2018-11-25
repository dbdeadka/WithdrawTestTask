<?php declare(strict_types=1);

namespace BITAPP\Form\Validator;

trait FormValidatorTrait
{
    /**
     * @var string
     */
    protected $error;

    /**
     * @var string
     */
    protected $customError = '';

    /**
     * @param string $customError
     * @return $this
     */
    public function setCustomError($customError)
    {
        $this->customError = $customError;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomError()
    {
        return $this->customError;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return $this
     */
    public function clearError()
    {
        $this->error = null;
        return $this;
    }

    public function isValid(): bool
    {
        return null === $this->error;
    }
}
