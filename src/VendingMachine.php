<?php

declare(strict_types=1);

namespace TDD;

class VendingMachine
{
    private float $balance = 0.00;

    public const ALLOWED_COINS = [
        'ONE_PENCE' => 0.01,
        'FIVE_PENCE' => 0.05,
        'TWENTY_PENCE' => 0.20,
        'FIFTY_PENCE' => 0.50,
        'ONE_POUND' => 1.00
    ];

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

    public function checkPenceBalance(): int
    {
        return (int) ($this->balance * 100);
    }

    private function doesCoinAccept(float $coin): bool
    {
        return in_array($coin, self::ALLOWED_COINS);
    }

    public function selectProduct(int $pences): ?bool
    {
        if($this->checkPenceBalance() >= $pences)
        {
            return true;
        }
    }
}
