<?php

declare(strict_types=1);

use TDD\VendingMachine;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
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

    public function test_vending_machine_shouldnt_accept_2_pence_coin(): void
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
}
