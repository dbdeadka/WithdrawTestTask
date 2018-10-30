<?php declare(strict_types=1);

namespace BITAPP\Core;

class FirewallStruct
{
    /**
     * @var string $controller
     */
    protected $controller;

    /**
     * @var string $method
     */
    protected $method;

    /**
     * @var string $redirectUri;
     */
    protected $redirectUri;

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

    public function setRedirectUri(?string $redirectUri): self
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }
}
