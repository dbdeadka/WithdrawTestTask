<?php declare(strict_types=1);

namespace BITAPP\Form;

use \BITAPP\Form\Validator\BaseFormValidator;
use \BITAPP\Form\Validator\FormValidatorTrait;
use \BITAPP\Form\Validator\ValidatorInterface;
use \BITAPP\Form\Converter\BaseConverter;

class RuleContainer extends AbstractDataValueManager implements ValidatorInterface
{
    use FormValidatorTrait;

    /** @var AbstractDataValueManager[] */
    protected $rules = [];
    protected $key = '';

    public function __construct(array $rules = [], $customError = '')
    {
        $this->setCustomError($customError);
        $this->setRules($rules);
    }

    /**
     * @return AbstractDataValueManager[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @param AbstractDataValueManager[] $rules
     * @return $this
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = (string) $key;
        return $this;
    }

    public function execute()
    {
        $filteredValue = $this->getValue();
        foreach ($this->getRules() as $rule) {
            $rule->setValue($filteredValue);
            if ($rule instanceof BaseConverter) {
                $filteredValue = $rule->convert();
            }
            if ($rule instanceof BaseFormValidator) {
                if ($this->getCustomError()) {
                    $rule->setCustomError($this->getCustomError());
                }
                $rule->check();
                if (!$rule->isValid()) {
                    $this->setError($rule->getError());
                    break;
                }
            }
        }
        return $filteredValue;
    }
}
