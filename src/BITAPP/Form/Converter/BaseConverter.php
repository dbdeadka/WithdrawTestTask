<?php declare(strict_types=1);

namespace BITAPP\Form\Converter;

use \BITAPP\Form\AbstractDataValueManager;
use \BITAPP\Form\CanBeEmptyTrait;

abstract class BaseConverter extends AbstractDataValueManager
{
    use CanBeEmptyTrait;

    abstract protected function doConvert();

    public function __construct(bool $canBeEmpty = false)
    {
        $this->setCanBeEmpty($canBeEmpty);
    }

    public function convert()
    {
        if ($this->isCanBeEmpty() && (null === $this->getValue() || $this->getValue() === '')) {
            return null;
        }
        return $this->doConvert();
    }
}
