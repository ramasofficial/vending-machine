<?php

declare(strict_types=1);

namespace TDD;

use TDD\Client\Transformer\ResponseInterface;

interface BalanceInterface
{
    public function addBalance(int $number): bool;

    public function setBalance(int $number): ?array;

    public function getBalance(): int;
}