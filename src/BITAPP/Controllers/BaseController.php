<?php declare(strict_types=1);

namespace BITAPP\Controllers;

abstract class BaseController
{
    /**
     * @return string
     */
    public function render404PageAction() :string
    {
        header('HTTP/1.0 404 Not Found');
        return '';
    }
}
