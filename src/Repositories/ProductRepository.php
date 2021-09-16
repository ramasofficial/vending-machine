<?php

declare(strict_types=1);

namespace TDD\Repositories;

use TDD\Exceptions\ProductNotFoundException;

class ProductRepository implements ProductRepositoryInterface
{
    public const PRODUCTS = [
        10 => 'CANDY',
        50 => 'SNACKS',
        75 => 'NUTS',
        100 => 'BOTTLE WATER',
        150 => 'COKE',
    ];

    public function getProductByPence(int $pence): ?array
    {
        if(!self::PRODUCTS[$pence]) {
            throw new ProductNotFoundException('Product does not exist!');
        }

        return [
            'product' => self::PRODUCTS[$pence],
            'price' => $pence,
        ];
    }
}