<?php

namespace CoffeeMachine\Exceptions\Payments;

use CoffeeMachine\Contracts\Payment\PaymentCurrency;
use Exception;

class InvalidPaymentCurrencyException extends Exception
{
    public function __construct(PaymentCurrency $currency, string $expectedCurrency)
    {
        parent::__construct(
            message: sprintf(
                "Invalid payment currency medium. Expected '%s' but received '%s' instead.",
                $expectedCurrency,
                get_class($currency)
            )
        );
    }
}