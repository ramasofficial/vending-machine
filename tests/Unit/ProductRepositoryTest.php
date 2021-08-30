<?php

declare(strict_types=1);

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use TDD\Repositories\ProductRepository;

class ProductRepositoryTest extends TestCase
{
    private ProductRepository $repository;

    public function setUp(): void
    {
        $this->repository = new ProductRepository();
    }

    public function test_should_initialize_product_repository_class(): void
    {
        $this->assertInstanceOf(ProductRepository::class, $this->repository);
    }
}