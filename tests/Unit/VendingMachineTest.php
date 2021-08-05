<?php

declare(strict_types=1);

namespace Test\Unit;

use InvalidArgumentException;
use TDD\VendingMachine;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    public const CANDY = 10;
    private VendingMachine $vendingMachine;

    protected function setUp(): void
    {
        $this->vendingMachine = new VendingMachine();
    }

    public function test_should_initialize_vending_machine_class(): void
    {
        $this->assertInstanceOf(VendingMachine::class, $this->vendingMachine);
    }

    public function test_vending_machine_should_accept_1_pence_coin(): void
    {
        $add = $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['ONE_PENCE']);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(VendingMachine::ALLOWED_COINS['ONE_PENCE'], $balance);
    }

    public function test_vending_machine_should_accept_5_pence_coin(): void
    {
        $add = $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(VendingMachine::ALLOWED_COINS['FIVE_PENCE'], $balance);
    }

    public function test_vending_machine_should_accept_20_pence_coin(): void
    {
        $add = $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['TWENTY_PENCE']);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(VendingMachine::ALLOWED_COINS['TWENTY_PENCE'], $balance);
    }

    public function test_vending_machine_should_accept_50_pence_coin(): void
    {
        $add = $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIFTY_PENCE']);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(VendingMachine::ALLOWED_COINS['FIFTY_PENCE'], $balance);
    }

    public function test_vending_machine_should_accept_1_pound_coin(): void
    {
        $add = $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['ONE_POUND'], 'pound');

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(100, $balance);
    }

    public function test_vending_machine_should_not_accept_2_pence_coin(): void
    {
        $add = $this->vendingMachine->add(2);

        $this->assertFalse($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(0, $balance);
    }

    public function test_vending_machine_should_accept_coins_multiple_times(): void
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['ONE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['TWENTY_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIFTY_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['ONE_POUND'], 'pound');

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(176, $balance);
    }

    public function test_vending_machine_return_balance_in_pence(): void
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['ONE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['ONE_POUND'], 'pound');

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(106, $balance);
    }

    public function test_user_can_select_product_in_pences_and_vending_machine_returns_selected_product()
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);

        $product = $this->vendingMachine->selectProduct(self::CANDY);

        $this->assertSame(VendingMachine::PRODUCTS[self::CANDY], $product);
    }

    public function test_user_dont_have_enough_money_to_buy_product()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User does not have enough money to buy this product!');

        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);

        $this->vendingMachine->selectProduct(self::CANDY);
    }

    public function test_reduce_vending_machine_balance_after_user_bought_product()
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);

        $this->vendingMachine->selectProduct(self::CANDY);

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(5, $balance);
    }
}
