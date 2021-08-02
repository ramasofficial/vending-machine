<?php

declare(strict_types=1);

namespace TDD;

class VendingMachine
{
    private $balance;

    public const ALLOWED_COINS = [0.01, 0.05, 0.20, 0.50, 1.00];

    public function add(float $coin): bool
    {
        if($this->doesCoinAccept($coin)) {
            $this->balance += $coin;

            return true;
        }

        return false;
    }

    public function checkBalance(): float
    {
        return $this->balance;
    }

    private function doesCoinAccept(float $coin): bool
    {
        return in_array($coin, self::ALLOWED_COINS);
    }
}
