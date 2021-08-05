<?php

declare(strict_types=1);

namespace TDD;

use InvalidArgumentException;

class VendingMachine
{
    private float $balance = 0.00;

    public const PRODUCTS = [
        10 => 'Candy',
        50 => 'SNACKS',
        75 => 'NUTS',
        100 => 'BOTTLE WATER',
        150 => 'COKE',
    ];

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

    public function selectProduct(int $pences): ?string
    {
        if($this->checkPenceBalance() >= $pences)
        {
            return self::PRODUCTS[(int) $pences];
        } else {
            throw new InvalidArgumentException('User does not have enough money to buy this product!');
        }
    }
}
