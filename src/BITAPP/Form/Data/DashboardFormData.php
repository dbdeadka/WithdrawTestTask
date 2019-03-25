<?php declare(strict_types=1);

namespace BITAPP\Form\Data;

use \BITAPP\Form\AbstractFormData;
use \BITAPP\Form\RuleContainer;
use \BITAPP\Form\Validator\PositiveInteger;
use \BITAPP\Form\Converter\Trim;

class DashboardFormData extends AbstractFormData
{
    protected $amount;

    /** {@inheritdoc} */
    protected function getRules(): array
    {
        return [
            'amount' => new RuleContainer([new Trim(), new PositiveInteger(false, 'Amount must be positive')])
        ];
    }

    public function getAmount() :int
    {
        return (int)$this->amount;
    }
}
