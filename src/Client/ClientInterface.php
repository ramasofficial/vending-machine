<?php

declare(strict_types=1);

namespace TDD\Client;

use TDD\Client\Transformer\ResponseInterface;

interface ClientInterface
{
    public function getBalance(): ?array;

    public function addBalance(int $amount): ?array;

    public function selectProduct(int $pence): ?array;

    public function refund(): ?array;
}