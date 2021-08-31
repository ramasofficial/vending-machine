<?php

declare(strict_types=1);

namespace TDD\Client;

use TDD\Client\Transformer\ResponseInterface;

interface ClientInterface
{
    public function getBalance(): ?ResponseInterface;

    public function addBalance(int $amount): ?ResponseInterface;

    public function selectProduct(int $pence): ?ResponseInterface;

    public function refund(): ?ResponseInterface;
}