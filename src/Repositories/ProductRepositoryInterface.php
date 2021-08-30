<?php

namespace TDD\Repositories;

interface ProductRepositoryInterface
{
    public function getProductByPence(int $pence): ?array;
}