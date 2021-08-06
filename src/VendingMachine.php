<?php

declare(strict_types=1);

namespace TDD;

use InvalidArgumentException;
use TDD\Repositories\ProductRepositoryInterface;

class VendingMachine
{
    private AddCoinInterface $addCoin;

    private BalanceInterface $balance;

    private ProductRepositoryInterface $repository;

    public function __construct(AddCoinInterface $addCoin, BalanceInterface $balance, ProductRepositoryInterface $repository)
    {
        $this->addCoin = $addCoin;
        $this->balance = $balance;
        $this->repository = $repository;
    }

    public function add(int $coin, $type = 'pence'): bool
    {
        return $this->addCoin->add($coin, $type);
    }

    public function checkBalance(): int
    {
        return $this->balance->getBalance();
    }

    public function selectProduct(int $pences): ?array
    {
        $product = $this->repository->getProductByPence($pences);

        if(!$this->haveEnoughMoney($product['price'])) {
            throw new InvalidArgumentException('User does not have enough money to buy this product!');
        }

        $balance = $this->balance->getBalance() - $pences;

        $this->balance->setBalance($balance);

        return [
            'selected_product' => $product['product'],
            'balance' => $this->balance->getBalance()
        ];
    }

    public function refund(): int
    {
        $balance = $this->balance->getBalance();

        $this->balance->setBalance(0);

        return $balance;
    }

    public function reset(): ?array
    {
        return [
            'balance' => $this->refund()
        ];
    }

    private function haveEnoughMoney(int $price): bool
    {
        return $price < $this->balance->getBalance();
    }
}
