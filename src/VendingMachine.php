<?php

declare(strict_types=1);

namespace TDD;

use InvalidArgumentException;

class VendingMachine
{
    private int $balance = 0;

    public const PRODUCTS = [
        10 => 'Candy',
        50 => 'SNACKS',
        75 => 'NUTS',
        100 => 'BOTTLE WATER',
        150 => 'COKE',
    ];

    public const ALLOWED_COINS = [
        'pence' => [
            'ONE_PENCE' => 1,
            'FIVE_PENCE' => 5,
            'TWENTY_PENCE' => 20,
            'FIFTY_PENCE' => 50,
        ],
        'pound' => [
            'ONE_POUND' => 1,
        ],
    ];

    public function add(int $coin, $type = 'pence'): bool
    {
        if($this->doesCoinAccept($coin, $type)) {

            if($type === 'pound') {
                $this->balance += $coin * 100;
            }

            if($type === 'pence') {
                $this->balance += $coin;
            }

            return true;
        }

        return false;
    }

    public function checkBalance(): int
    {
        return $this->balance;
    }

    private function doesCoinAccept(float $coin, string $type): bool
    {
        return in_array($coin, self::ALLOWED_COINS[$type]);
    }

    public function selectProduct(int $pences): ?array
    {
        if(!$this->haveEnoughMoney($pences)) {
            throw new InvalidArgumentException('User does not have enough money to buy this product!');
        }

        $this->balance = $this->checkBalance() - $pences;

        return [
            'selected_product' => self::PRODUCTS[(int) $pences],
            'balance' => $this->checkBalance()
        ];
    }

    private function haveEnoughMoney(int $pences): bool
    {
        return $this->checkBalance() >= $pences;
    }

    public function refund(): int
    {
        $balance = $this->balance;

        $this->balance = 0;

        return $balance;
    }
}
