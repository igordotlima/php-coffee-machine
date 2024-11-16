<?php

namespace CoffeeMachine\Common\Payments;


use CoffeeMachine\Contracts\Payment\PaymentCurrency;

abstract class BasePaymentCurrency implements PaymentCurrency
{
    /**
     * The commercial value of the currency.
     *
     * @var int
     */
    private readonly int $value;

    /**
     * Creates an instance of a currency.
     *
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}