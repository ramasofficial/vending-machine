<?php

declare(strict_types=1);

namespace TDD;

class VendingMachine
{
    private $balance;

    public function add(float $coin): bool
    {
        if($coin === 0.01) {
            $this->balance += 0.01;
            return true;
        }

        if($coin === 0.05) {
            $this->balance += 0.05;
            return true;
        }

        if($coin === 0.20) {
            $this->balance += 0.20;
            return true;
        }

        if($coin === 0.50) {
            $this->balance += 0.50;
            return true;
        }

        if($coin === 1.00) {
            $this->balance += 1.00;
            return true;
        }

        return false;
    }

    public function checkBalance(): float
    {
        return $this->balance;
    }
}
