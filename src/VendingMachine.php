<?php

declare(strict_types=1);

namespace TDD;

use TDD\Client\ClientInterface;
use TDD\Exceptions\DoesntHaveEnoughMoney;
use TDD\Repositories\ProductRepositoryInterface;

class VendingMachine
{
    private AddCoinInterface $coin;

    private BalanceInterface $balance;

    private ProductRepositoryInterface $repository;

    private ClientInterface $client;

    public function __construct(AddCoinInterface $coin, BalanceInterface $balance, ProductRepositoryInterface $repository, ClientInterface $client)
    {
        $this->coin = $coin;
        $this->balance = $balance;
        $this->repository = $repository;
        $this->client = $client;
    }

    public function add(int $coin, $type = 'pence'): bool
    {
        return $this->coin->add($coin, $type);
    }

    public function checkBalance(): int
    {
        return $this->balance->getBalance();
    }

    public function selectProduct(int $pences): ?array
    {
        $product = $this->repository->getProductByPence($pences);

        if(!$this->haveEnoughMoney($product['price'])) {
            throw new DoesntHaveEnoughMoney('User does not have enough money to buy this product!');
        }

        return $this->client->selectProduct($pences);
    }

    public function refund(): ?array
    {
        return $this->client->refund();
    }

    public function reset(): ?array
    {
        return $this->refund();
    }

    private function haveEnoughMoney(int $price): bool
    {
        return $price <= $this->balance->getBalance();
    }
}
