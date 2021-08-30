<?php

declare(strict_types=1);

namespace TDD;

interface AddCoinInterface
{
    public function add(int $coin, string $type = 'pence'): bool;
}