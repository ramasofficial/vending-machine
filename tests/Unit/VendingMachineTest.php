<?php

declare(strict_types=1);

namespace Test\Unit;

use TDD\AddCoin;
use TDD\Balance;
use TDD\Client\Client;
use TDD\Exceptions\DoesntHaveEnoughMoney;
use TDD\Repositories\ProductRepository;
use TDD\VendingMachine;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    private const SNACKS = 50;
    private const BOTTLE_OF_WATER = 100;
    private const ZERO_BALANCE = 0;
    private const PENCE = 'pence';
    private const ONE_HUNDRED = 100;
    private const EXPECTED_PRODUCT = ['selected_product' => 'BOTTLE OF WATER', 'current_balance' => 200];
    private const EXPECTED_PRODUCT_2 = ['selected_product' => 'SNACKS', 'current_balance' => 150];
    private const FIFTY_PENCES = 50;
    private const THREE_HUNDRED_PENCES = 300;
    private const TWO_HUNDRED_PENCES = 200;

    private VendingMachine $vendingMachine;
    private AddCoin $coin;
    private Balance $balance;
    private ProductRepository $repository;
    private Client $client;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->balance = new Balance($this->client);
        $this->coin = new AddCoin($this->balance);
        $this->repository = new ProductRepository();
        $this->vendingMachine = new VendingMachine($this->coin, $this->balance, $this->repository, $this->client);
    }

    public function test_should_initialize_vending_machine_class(): void
    {
        $this->assertInstanceOf(VendingMachine::class, $this->vendingMachine);
    }

    public function test_should_initialize_add_coin_class(): void
    {
        $this->assertInstanceOf(AddCoin::class, $this->coin);
    }

    public function test_should_initialize_balance_class(): void
    {
        $this->assertInstanceOf(Balance::class, $this->balance);
    }

    public function test_should_initialize_product_repository_class(): void
    {
        $this->assertInstanceOf(ProductRepository::class, $this->repository);
    }

    public function test_can_add_coin_to_vending_machine(): void
    {
        $actual = $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['ONE_PENCE']);

        $this->assertTrue($actual);
    }

    public function test_can_check_balance_in_vending_machine(): void
    {
        $this->client->method('getBalance')->willReturn(['balance' => self::ONE_HUNDRED]);

        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIFTY_PENCE']);
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIFTY_PENCE']);

        $actual = $this->vendingMachine->checkBalance();

        $this->assertSame(self::ONE_HUNDRED, $actual);
    }

    public function test_user_can_select_product_and_vending_machine_returns_selected_product(): void
    {
        $this->client->method('getBalance')->willReturn(['balance' => self::THREE_HUNDRED_PENCES]);
        $this->client->method('selectProduct')->willReturn(self::EXPECTED_PRODUCT);

        $actual = $this->vendingMachine->selectProduct(self::BOTTLE_OF_WATER);

        $this->assertSame(self::EXPECTED_PRODUCT, $actual);
    }

    public function test_user_can_select_multiple_products_with_existing_balance(): void
    {
        $this->client->method('getBalance')->will($this->onConsecutiveCalls(['balance' => self::THREE_HUNDRED_PENCES], ['balance' => self::TWO_HUNDRED_PENCES]));
        $this->client->method('selectProduct')->will($this->onConsecutiveCalls(self::EXPECTED_PRODUCT, self::EXPECTED_PRODUCT_2));

        $actual = $this->vendingMachine->selectProduct(self::BOTTLE_OF_WATER);
        $actualSecond = $this->vendingMachine->selectProduct(self::SNACKS);

        $this->assertSame(self::EXPECTED_PRODUCT, $actual);
        $this->assertSame(self::EXPECTED_PRODUCT_2, $actualSecond);
    }

    public function test_user_dont_have_enough_money_to_buy_product(): void
    {
        $this->expectException(DoesntHaveEnoughMoney::class);

        $this->client->method('getBalance')->willReturn(['balance' => self::FIFTY_PENCES]);

        $this->vendingMachine->selectProduct(self::BOTTLE_OF_WATER);
    }

    public function test_reduce_vending_machine_balance_after_user_bought_product(): void
    {
        $this->client->method('getBalance')->will($this->onConsecutiveCalls(['balance' => self::THREE_HUNDRED_PENCES], ['balance' => self::TWO_HUNDRED_PENCES]));
        $this->client->method('selectProduct')->will($this->onConsecutiveCalls(self::EXPECTED_PRODUCT, self::EXPECTED_PRODUCT_2));

        $this->vendingMachine->selectProduct(self::BOTTLE_OF_WATER);

        $actual = $this->vendingMachine->checkBalance();

        $this->assertSame(self::TWO_HUNDRED_PENCES, $actual);
    }

    public function test_user_can_get_refund_if_canceling_request(): void
    {
        $this->client->method('refund')->willReturn(['refund' => self::TWO_HUNDRED_PENCES]);

        $actual = $this->vendingMachine->refund();

        $this->assertSame(self::TWO_HUNDRED_PENCES, $actual['refund']);
    }

    public function test_vending_machine_is_empty_balance_if_user_took_refund(): void
    {
        $this->client->method('getBalance')->willReturn(['balance' => self::ZERO_BALANCE]);
        $this->client->method('refund')->willReturn(['refund' => self::TWO_HUNDRED_PENCES]);

        $this->vendingMachine->refund();

        $actual = $this->vendingMachine->checkBalance();

        $this->assertSame(self::ZERO_BALANCE, $actual);
    }

    public function test_vending_machine_return_selected_product_and_remaining_change(): void
    {
        $this->client->method('getBalance')->willReturn(['balance' => self::THREE_HUNDRED_PENCES]);
        $this->client->method('selectProduct')->willReturn(self::EXPECTED_PRODUCT);

        $actual = $this->vendingMachine->selectProduct(self::BOTTLE_OF_WATER);

        $this->assertSame(self::EXPECTED_PRODUCT, $actual);
    }

    public function test_vending_machine_can_be_reset(): void
    {
        $this->client->method('refund')->willReturn(['refund' => self::TWO_HUNDRED_PENCES]);

        $actual = $this->vendingMachine->reset();

        $this->assertSame(self::TWO_HUNDRED_PENCES, $actual['refund']);
    }
}
