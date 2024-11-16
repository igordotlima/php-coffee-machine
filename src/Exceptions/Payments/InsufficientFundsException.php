<?php

namespace CoffeeMachine\Exceptions\Payments;

use Exception;

class InsufficientFundsException extends Exception
{
    public function __construct()
    {
        parent::__construct('Insufficient funds.');
    }
}