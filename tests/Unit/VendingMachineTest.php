<?php

declare(strict_types=1);

namespace Test\Unit;

use InvalidArgumentException;
use TDD\VendingMachine;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    public const CANDY = 10;
    public const ONE_POUND_TO_PENCES = 100;
    public const ZERO_BALANCE = 0;
    private const PENCE = 'pence';
    private const POUND = 'pound';
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
        $add = $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['ONE_PENCE']);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(VendingMachine::ALLOWED_COINS[self::PENCE]['ONE_PENCE'], $balance);
    }

    public function test_vending_machine_should_accept_5_pence_coin(): void
    {
        $add = $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE'], $balance);
    }

    public function test_vending_machine_should_accept_20_pence_coin(): void
    {
        $add = $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['TWENTY_PENCE']);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(VendingMachine::ALLOWED_COINS[self::PENCE]['TWENTY_PENCE'], $balance);
    }

    public function test_vending_machine_should_accept_50_pence_coin(): void
    {
        $add = $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIFTY_PENCE']);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(VendingMachine::ALLOWED_COINS[self::PENCE]['FIFTY_PENCE'], $balance);
    }

    public function test_vending_machine_should_accept_1_pound_coin(): void
    {
        $add = $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::POUND]['ONE_POUND'], self::POUND);

        $this->assertTrue($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(self::ONE_POUND_TO_PENCES, $balance);
    }

    public function test_vending_machine_should_not_accept_2_pence_coin(): void
    {
        $add = $this->vendingMachine->add(2);

        $this->assertFalse($add);
        
        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(self::ZERO_BALANCE, $balance);
    }

    public function test_vending_machine_should_accept_coins_multiple_times(): void
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['ONE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['TWENTY_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIFTY_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::POUND]['ONE_POUND'], self::POUND);

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(176, $balance);
    }

    public function test_vending_machine_return_balance_in_pence(): void
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['ONE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::POUND]['ONE_POUND'], self::POUND);

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(106, $balance);
    }

    public function test_user_can_select_product_in_pences_and_vending_machine_returns_selected_product()
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);

        $product = $this->vendingMachine->selectProduct(self::CANDY);

        $this->assertContains(VendingMachine::PRODUCTS[self::CANDY], $product);

        $this->assertArrayHasKey('selected_product', $product);
    }

    public function test_user_dont_have_enough_money_to_buy_product()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User does not have enough money to buy this product!');

        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);

        $this->vendingMachine->selectProduct(self::CANDY);
    }

    public function test_reduce_vending_machine_balance_after_user_bought_product()
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);

        $this->vendingMachine->selectProduct(self::CANDY);

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(5, $balance);
    }

    public function test_user_can_get_refund_if_canceling_request()
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);

        $refund = $this->vendingMachine->refund();

        $this->assertSame(10, $refund);
    }

    public function test_vending_machine_is_empty_balance_if_user_took_refund()
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);

        $this->vendingMachine->refund();

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(self::ZERO_BALANCE, $balance);
    }

    public function test_vending_machine_return_selected_product_and_remaining_change()
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::PENCE]['FIVE_PENCE']);
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::POUND]['ONE_POUND'], self::POUND);

        $product = $this->vendingMachine->selectProduct(self::CANDY);

        $this->assertContains(VendingMachine::PRODUCTS[self::CANDY], $product);

        $this->assertArrayHasKey('selected_product', $product);

        $this->assertContains(100, $product);

        $this->assertArrayHasKey('balance', $product);
    }
    
    public function test_vending_machine_can_be_reseted()
    {
        $this->vendingMachine->add(VendingMachine::ALLOWED_COINS[self::POUND]['ONE_POUND'], self::POUND);

        $reset = $this->vendingMachine->reset();

        $this->assertContains(100, $reset);

        $this->assertArrayHasKey('balance', $reset);

        $balance = $this->vendingMachine->checkBalance();

        $this->assertSame(self::ZERO_BALANCE, $balance);
    }
}
