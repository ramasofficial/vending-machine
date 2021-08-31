<?php

declare(strict_types=1);

namespace TDD;

use TDD\Client\ClientInterface;

class Balance implements BalanceInterface
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function addBalance(int $number): bool
    {
        $this->client->addBalance($number);

        return true;
    }

    public function getBalance(): int
    {
        return $this->client->getBalance()['balance'];
    }
}