<?php

declare(strict_types=1);

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use TDD\AddCoin;
use TDD\Balance;

class AddCoinTest extends TestCase
{
    private const PENCE = 'pence';
    private const POUND = 'pound';
    private const ONE_POUND_TO_PENCES = 100;
    private const ZERO_BALANCE = 0;
    private AddCoin $coin;
    private Balance $balance;

    public function setUp(): void
    {
        $this->balance = new Balance();
        $this->coin = new AddCoin($this->balance);
    }

    public function test_should_initialize_add_coin_class(): void
    {
        $this->assertInstanceOf(AddCoin::class, $this->coin);
    }

    public function test_should_initialize_balance_class(): void
    {
        $this->assertInstanceOf(Balance::class, $this->balance);
    }

    public function test_add_coin_should_accept_1_pence_coin(): void
    {
        $add = $this->coin->add(AddCoin::ALLOWED_COINS[self::PENCE]['ONE_PENCE']);

        $this->assertTrue($add);

        $balance = $this->balance->getBalance();

        $this->assertSame(AddCoin::ALLOWED_COINS[self::PENCE]['ONE_PENCE'], $balance);
    }

    public function test_add_coin_should_accept_5_pence_coin(): void
    {
        $add = $this->coin->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);

        $this->assertTrue($add);

        $balance = $this->balance->getBalance();

        $this->assertSame(AddCoin::ALLOWED_COINS[self::PENCE]['FIVE_PENCE'], $balance);
    }

    public function test_add_coin_should_accept_20_pence_coin(): void
    {
        $add = $this->coin->add(AddCoin::ALLOWED_COINS[self::PENCE]['TWENTY_PENCE']);

        $this->assertTrue($add);

        $balance = $this->balance->getBalance();

        $this->assertSame(AddCoin::ALLOWED_COINS[self::PENCE]['TWENTY_PENCE'], $balance);
    }

    public function test_add_coin_should_accept_50_pence_coin(): void
    {
        $add = $this->coin->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIFTY_PENCE']);

        $this->assertTrue($add);

        $balance = $this->balance->getBalance();

        $this->assertSame(AddCoin::ALLOWED_COINS[self::PENCE]['FIFTY_PENCE'], $balance);
    }

    public function test_add_coin_should_accept_1_pound_coin(): void
    {
        $add = $this->coin->add(AddCoin::ALLOWED_COINS[self::POUND]['ONE_POUND'], self::POUND);

        $this->assertTrue($add);

        $balance = $this->balance->getBalance();

        $this->assertSame(self::ONE_POUND_TO_PENCES, $balance);
    }

    public function test_add_coin_should_not_accept_2_pence_coin(): void
    {
        $add = $this->coin->add(2);

        $this->assertFalse($add);

        $balance = $this->balance->getBalance();

        $this->assertSame(self::ZERO_BALANCE, $balance);
    }
}