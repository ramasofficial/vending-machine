<?php

declare(strict_types=1);

namespace TDD;

class Balance implements BalanceInterface
{
    private int $balance = 0;

    public function addBalance(int $number): bool
    {
        $this->balance += $number;

        return true;
    }

    public function setBalance(int $number): bool
    {
        $this->balance = $number;

        return true;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }
}