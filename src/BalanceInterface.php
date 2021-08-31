<?php

declare(strict_types=1);

namespace TDD;

interface BalanceInterface
{
    public function addBalance(int $number): bool;

    public function getBalance(): int;
}