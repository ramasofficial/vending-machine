<?php

declare(strict_types=1);

namespace Test\Unit;

use Dotenv\Dotenv;
use InvalidArgumentException;
use TDD\AddCoin;
use TDD\Balance;
use TDD\Client\Transformer\ApiResponse;
use TDD\Repositories\ProductRepository;
use TDD\VendingMachine;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    private const CANDY = 10;
    private const SNACKS = 50;
    private const ZERO_BALANCE = 0;
    private const PENCE = 'pence';
    private const POUND = 'pound';
    private const FORTY = 40;
    private const ONE_HUNDRED = 100;

    private VendingMachine $vendingMachine;
    private AddCoin $coin;
    private Balance $balance;
    private ProductRepository $repository;

    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();

        $this->balance = new Balance();
        $this->coin = new AddCoin($this->balance);
        $this->repository = new ProductRepository();
        $this->vendingMachine = new VendingMachine($this->coin, $this->balance, $this->repository);
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
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['TWENTY_PENCE']);

        $balance = $this->balance->getBalance();

        $this->assertSame(AddCoin::ALLOWED_COINS[self::PENCE]['TWENTY_PENCE'], $balance);
    }

    public function test_can_check_balance_in_vending_machine(): void
    {
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['TWENTY_PENCE']);
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['TWENTY_PENCE']);

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(self::FORTY, $balance);
    }

    public function test_user_can_select_product_and_vending_machine_returns_selected_product(): void
    {
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['TWENTY_PENCE']);
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['TWENTY_PENCE']);

        $product = $this->vendingMachine->selectProduct(self::CANDY);

        $this->assertContains(ProductRepository::PRODUCTS[self::CANDY], $product);

        $this->assertArrayHasKey('selected_product', $product);
    }

    public function test_user_can_select_multiple_products_with_existing_balance(): void
    {
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIFTY_PENCE']);
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIFTY_PENCE']);

        $this->vendingMachine->selectProduct(self::CANDY);
        $this->vendingMachine->selectProduct(self::SNACKS);

        $refund = $this->vendingMachine->refund();

        $this->assertSame(self::FORTY, $refund);
    }

    public function test_user_dont_have_enough_money_to_buy_product(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User does not have enough money to buy this product!');

        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['ONE_PENCE']);

        $this->vendingMachine->selectProduct(self::CANDY);
    }

    public function test_reduce_vending_machine_balance_after_user_bought_product(): void
    {
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);

        $this->vendingMachine->selectProduct(self::CANDY);

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(5, $balance);
    }

    public function test_user_can_get_refund_if_canceling_request(): void
    {
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);

        $refund = $this->vendingMachine->refund();

        $this->assertSame(10, $refund);
    }

    public function test_vending_machine_is_empty_balance_if_user_took_refund(): void
    {
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);

        $this->vendingMachine->refund();

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(self::ZERO_BALANCE, $balance);
    }

    public function test_vending_machine_return_selected_product_and_remaining_change(): void
    {
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::POUND]['ONE_POUND'], self::POUND);

        $product = $this->vendingMachine->selectProduct(self::CANDY);

        $this->assertContains(ProductRepository::PRODUCTS[self::CANDY], $product);

        $this->assertArrayHasKey('selected_product', $product);

        $this->assertContains(self::ONE_HUNDRED, $product);

        $this->assertArrayHasKey('balance', $product);
    }

    public function test_vending_machine_can_be_reset(): void
    {
        $this->vendingMachine->add(AddCoin::ALLOWED_COINS[self::POUND]['ONE_POUND'], self::POUND);

        $reset = $this->vendingMachine->reset();

        $this->assertContains(self::ONE_HUNDRED, $reset);

        $this->assertArrayHasKey('balance', $reset);

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(self::ZERO_BALANCE, $balance);
    }
}
