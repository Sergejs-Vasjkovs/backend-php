<?php

declare(strict_types=1);

namespace App\Core\Http;

use Laminas\Diactoros\ServerRequestFactory;

class Request
{
    private static ?self $instance = null;
    private \Psr\Http\Message\ServerRequestInterface $request;

    private function __construct()
    {
        $this->request = ServerRequestFactory::fromGlobals();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getBody(): array
    {
        return (array) json_decode((string) $this->request->getBody(), true);
    }

    public function getQueryParams(): array
    {
        return $this->request->getQueryParams();
    }

    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    public function getUri(): string
    {
        return $this->request->getUri()->getPath();
    }
}
