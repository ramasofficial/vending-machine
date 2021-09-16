<?php

declare(strict_types=1);

namespace TDD\Client\Transformer;

interface ResponseInterface
{
    public function transform(\Psr\Http\Message\ResponseInterface $response): ?array;
}