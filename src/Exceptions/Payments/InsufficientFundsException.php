<?php

namespace CoffeeMachine\Exceptions\Payments;

use Exception;

class InsufficientFundsException extends Exception
{
    public function __construct(int $expected)
    {
        parent::__construct(
            message: "Insufficient funds. (Expected: $expected)");
    }
}