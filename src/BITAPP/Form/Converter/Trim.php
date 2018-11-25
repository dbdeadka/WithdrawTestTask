<?php declare(strict_types=1);

namespace BITAPP\Form\Converter;

class Trim extends BaseConverter
{
    public function doConvert()
    {
        return trim($this->getValue());
    }
}
