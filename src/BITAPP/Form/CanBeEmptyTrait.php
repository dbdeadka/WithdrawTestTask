<?php declare(strict_types=1);

namespace BITAPP\Form;

trait CanBeEmptyTrait
{
    protected $canBeEmpty = false;

    public function isCanBeEmpty(): bool
    {
        return $this->canBeEmpty;
    }

    /**
     * @param bool $canBeEmpty
     * @return $this
     */
    public function setCanBeEmpty(bool $canBeEmpty)
    {
        $this->canBeEmpty = $canBeEmpty;
        return $this;
    }
}
