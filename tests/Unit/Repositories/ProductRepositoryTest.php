<?php

declare(strict_types=1);

namespace Test\Unit\Repositories;

use PHPUnit\Framework\TestCase;
use TDD\Repositories\ProductRepository;

class ProductRepositoryTest extends TestCase
{
    private const SNACKS = 50;

    private ProductRepository $repository;

    public function setUp(): void
    {
        $this->repository = new ProductRepository();
    }

    public function test_should_initialize_product_repository_class(): void
    {
        $this->assertInstanceOf(ProductRepository::class, $this->repository);
    }

    public function test_repository_should_return_product_array(): void
    {
        $actual = $this->repository->getProductByPence(self::SNACKS);

        $this->assertIsArray($actual);
    }
}