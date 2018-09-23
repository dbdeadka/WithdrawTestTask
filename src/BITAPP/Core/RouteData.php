<?php declare(strict_types=1);

namespace BITAPP\Core;

class RouteData
{
    /**
     * @var string $controller
     */
    protected $controller;

    /**
     * @var string $method
     */
    protected $method;

    public function setController(?string $controller): self
    {
        $this->controller = $controller;
        return $this;
    }

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function setMethod(?string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }
}
