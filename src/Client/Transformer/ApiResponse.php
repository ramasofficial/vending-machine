<?php

declare(strict_types=1);

namespace TDD\Client\Transformer;

use Psr\Http\Message\ResponseInterface;

class ApiResponse implements \TDD\Client\Transformer\ResponseInterface
{
    private ResponseInterface $response;

    public function transform(ResponseInterface $response): ?array
    {
        $body = $this->fromResponse($response);

        return $this->toArray($body);
    }

    private function toArray(string $string): ?array
    {
        return json_decode($string, true);
    }

    private function fromResponse(ResponseInterface $response): string
    {
        return $response->getBody()->getContents();
    }
}