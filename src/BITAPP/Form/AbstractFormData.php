<?php declare(strict_types=1);

namespace BITAPP\Form;

use \BITAPP\Form\Validator\ValidatorInterface;
use \BITAPP\Form\Validator\FormValidatorTrait;

abstract class AbstractFormData implements ValidatorInterface
{
    use FormValidatorTrait;

    /**
     * @var array
     */
    protected $sourceData;

    /**
     * @param string[] $error
     */
    private $errors = [];

    /**
     * @return RuleContainer[]
     */
    abstract protected function getRules(): array;

    /**
     * @param array $data
     * @throws \RuntimeException
     */
    public function __construct(array $data)
    {
        $this->sourceData = $data;
        $this->execute();
    }

    /**
     * @throws \RuntimeException
     */
    public function execute()
    {
        if (empty($this->errors)) {
            foreach ($this->getRules() as $param => $ruleContainer) {
                if (!property_exists($this, $param)) {
                    throw new \RuntimeException(
                        'Class "' . self::class . '" does not contain "' . $param . '" attribute'
                    );
                }
                $value = $this->sourceData[$param] ?? null;
                $ruleContainer->setValue($value);
                if ($this->getCustomError() !== '') {
                    $ruleContainer->setCustomError($this->getCustomError());
                }
                $filteredValue = $ruleContainer->execute();
                if (!$ruleContainer->isValid()) {
                    $this->errors[$param] = $ruleContainer->getError();
                }
                $this->$param = $filteredValue;
            }
        }
    }

    /**
     * @return string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }


    /**
     * @return string[]
     */
    public function getFormsErrorsData(): array
    {
        if ($this->isValid()) {
            return [];
        }

        $result = [];
        foreach ($this->errors as $param => $error) {
            $result[$param] = $error;
        }
        return $result;
    }
}
