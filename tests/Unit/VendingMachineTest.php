<?php

declare(strict_types=1);

namespace Test\Unit;

use InvalidArgumentException;
use TDD\VendingMachine;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
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
        $add = $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['ONE_POUND']);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(VendingMachine::ALLOWED_COINS['ONE_POUND'], $balance);
    }

    public function test_vending_machine_should_not_accept_2_pence_coin(): void
    {
        $add = $this->vendingMachine->add(0.02);

        $this->assertFalse($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(0.00, $balance);
    }

    public function test_vending_machine_should_accept_coins_multiple_times(): void
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['ONE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['TWENTY_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIFTY_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['ONE_POUND']);

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(1.76, $balance);
    }

    public function test_vending_machine_return_balance_in_pounds(): void
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['ONE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(0.06, $balance);
    }

    public function test_vending_machine_return_balance_in_pence(): void
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['ONE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);

        $balance = $this->vendingMachine->checkPenceBalance();

        $this->assertSame(6, $balance);
    }

    public function test_user_can_select_product_in_pences()
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);

        $product = $this->vendingMachine->selectProduct(10);

        $this->assertTrue($product);
    }

    public function test_user_dont_have_enough_money_to_buy_product()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User does not have enough money to buy this product!');

        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS['FIVE_PENCE']);

        $this->vendingMachine->selectProduct(10);
    }
}
