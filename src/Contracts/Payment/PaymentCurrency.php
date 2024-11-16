<?php

namespace CoffeeMachine\Contracts\Payment;

interface PaymentCurrency
{
    /**
     * Get the currency singular name.
     *
     * @return string
     */
    public function getSingularName(): string;

    /**
     * Get the currency singular name.
     *
     * @return string
     */
    public function getPluralName(): string;

    /**
     * Get the currency commercial value.
     *
     * @return int
     */
    public function getValue(): int;
}