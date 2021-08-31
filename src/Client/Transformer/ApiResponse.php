<?php

declare(strict_types=1);

namespace TDD\Client\Transformer;

use Psr\Http\Message\ResponseInterface;

class ApiResponse implements \TDD\Client\Transformer\ResponseInterface
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public static function fromResponse(ResponseInterface $response): self
    {
        return new self($response);
    }

    public function toArray(): ?array
    {
        $body = $this->fromRequest();

        return json_decode($body, true);
    }

    private function fromRequest(): string
    {
        return $this->response->getBody()->getContents();
    }
}