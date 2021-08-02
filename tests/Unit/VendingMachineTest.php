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

    public function test_vending_machine_should_accept_1_pound_coin(): void
    {
        $add = $this->vendingMachine->add(1);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(1, $balance);
    }

    public function test_vending_machine_should_accept_5_pound_coin(): void
    {
        $add = $this->vendingMachine->add(5);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(5, $balance);
    }

    public function test_vending_machine_should_accept_20_pound_coin(): void
    {
        $add = $this->vendingMachine->add(20);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(20, $balance);
    }

    public function test_vending_machine_should_accept_50_pound_coin(): void
    {
        $add = $this->vendingMachine->add(50);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(50, $balance);
    }
}
