<?php

declare(strict_types=1);

namespace TDD;

use TDD\Exceptions\CoinNotAccepted;

class AddCoin implements AddCoinInterface
{
    private BalanceInterface $balance;

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

    public function __construct(BalanceInterface $balance)
    {
        $this->balance = $balance;
    }

    public function add(int $coin, string $type = 'pence'): bool
    {
        if(!$this->doesCoinAccept($coin, $type) || !array_key_exists($type, self::ALLOWED_COINS)) {
            throw new CoinNotAccepted('This coin not accepted!');
        }

        if($type === 'pound') {
            $coin *= 100;
        }

        return $this->balance->addBalance($coin);
    }

    private function doesCoinAccept(float $coin, string $type): bool
    {
        return in_array($coin, self::ALLOWED_COINS[$type]);
    }
}