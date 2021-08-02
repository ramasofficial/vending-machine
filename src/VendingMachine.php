<?php

declare(strict_types=1);

namespace TDD;

class VendingMachine
{
    private $balance;

    public function add($coin): bool
    {
        if($coin === 1) {
            $this->balance += 1;
            return true;
        }

        if($coin === 5) {
            $this->balance += 5;
            return true;
        }

        return false;
    }

    public function checkBalance()
    {
        return $this->balance;
    }
}
