<?php declare(strict_types=1);

namespace BITAPP\Core;

class TemplateFromURIData
{
    /**
     * @var array $params
     */
    protected $params = [];

    /**
     * @var array $errors
     */
    protected $errors = [];

    public function setParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
