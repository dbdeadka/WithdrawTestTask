<?php declare(strict_types=1);

namespace BITAPP\Services;

class Money
{
    /**
     * @param int $amount
     * @return string
     * @throws \RuntimeException
     */
    public static function moneyFormat(int $amount) : string
    {
        return sprintf('%.8f', $amount/100000000);
    }
}
