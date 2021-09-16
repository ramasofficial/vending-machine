<?php

declare(strict_types=1);

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use TDD\Balance;
use TDD\Client\Client;

class BalanceTest extends TestCase
{
    private const ONE_HUNDRED = 100;

    private const TEN = 10;

    private Balance $balance;

    private Client $client;

    public function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->balance = new Balance($this->client);
    }

    public function test_should_initialize_balance_class(): void
    {
        $this->assertInstanceOf(Balance::class, $this->balance);
    }

    public function test_can_add_balance(): void
    {
        $actual = $this->balance->addBalance(self::TEN);

        $this->assertTrue($actual);
    }

    public function test_can_check_balance(): void
    {
        $this->client->method('getBalance')->willReturn(['balance' => self::ONE_HUNDRED]);

        $actual = $this->balance->getBalance();

        $this->assertSame(self::ONE_HUNDRED, $actual);
    }
}