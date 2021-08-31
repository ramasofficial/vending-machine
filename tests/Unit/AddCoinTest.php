<?php

declare(strict_types=1);

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use TDD\AddCoin;
use TDD\Balance;
use TDD\Client\Client;
use TDD\Exceptions\CoinNotAccepted;

class AddCoinTest extends TestCase
{
    private const PENCE = 'pence';

    private const POUND = 'pound';

    private const COIN_NOT_ACCEPTED_MESSAGE = 'This coin not accepted!';

    private AddCoin $coin;

    private Balance $balance;

    private Client $client;

    public function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->balance = new Balance($this->client);
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

    public function test_should_initialize_client_class(): void
    {
        $this->assertInstanceOf(Client::class, $this->client);
    }

    /**
     * @dataProvider correctDataProvider
     */
    public function test_add_coin_should_accept_coin(int $number, string $type): void
    {
        $add = $this->coin->add($number, $type);

        $this->assertTrue($add);
    }

    /**
     * @dataProvider badDataProvider
     */
    public function test_add_coin_should_throw_exception_if_coin_not_accepted(): void
    {
        $this->expectException(CoinNotAccepted::class);
        $this->expectExceptionMessage(self::COIN_NOT_ACCEPTED_MESSAGE);

        $this->coin->add(2);
    }

    public function correctDataProvider(): array
    {
        return [
            [AddCoin::ALLOWED_COINS[self::PENCE]['ONE_PENCE'], self::PENCE],
            [AddCoin::ALLOWED_COINS[self::PENCE]['FIVE_PENCE'], self::PENCE],
            [AddCoin::ALLOWED_COINS[self::PENCE]['TWENTY_PENCE'], self::PENCE],
            [AddCoin::ALLOWED_COINS[self::PENCE]['FIFTY_PENCE'], self::PENCE],
            [AddCoin::ALLOWED_COINS[self::POUND]['ONE_POUND'], self::POUND]
        ];
    }

    public function badDataProvider(): array
    {
        return [
            [2, self::PENCE],
            [6, self::PENCE],
            [2, self::POUND]
        ];
    }
}