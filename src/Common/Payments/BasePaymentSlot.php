<?php

namespace CoffeeMachine\Common\Payments;

use CoffeeMachine\Contracts\Payment\PaymentCurrency;
use CoffeeMachine\Contracts\Payment\PaymentSlot;
use InvalidArgumentException;

abstract class BasePaymentSlot implements PaymentSlot
{
    /**
     * The inserted currency amount in the slot.
     *
     * @var int
     */
    private int $pendingAmount = 0;

    /**
     * The captured currency amount in the slot.
     * @var int
     */
    private int $capturedAmount = 0;

    public function getPendingAmount(): int
    {
        return $this->pendingAmount;
    }

    public function getCapturedAmount(): int
    {
        return $this->capturedAmount;
    }

    public function insert(PaymentCurrency $currency): void
    {
        $this->pendingAmount += $currency->getValue();
    }

    public function capture(int $amount): int
    {
        if ($amount > $this->getPendingAmount()) {
            throw new InvalidArgumentException(
                message: 'You attempted to capture more than the payment slot has.'
            );
        }

        // Here the client cannot refund the currency anymore.
        $this->pendingAmount -= $amount;

        // Here we move the currency into the vault permanently.
        $this->capturedAmount += $amount;

        return $amount;
    }

    public function refund(): int
    {
        // Save the current amount in the slot.
        $currentAmount = $this->getPendingAmount();

        // Give back the amount to the client.
        $this->pendingAmount -= $currentAmount;

        // Return the amount refunded to the client.
        return $currentAmount;
    }

    public function withdraw(int $amount): int
    {
        if ($amount > $this->getPendingAmount()) {
            throw new InvalidArgumentException(
                message: 'You attempted to withdraw more than the payment slot has.'
            );
        }

        $this->pendingAmount -= $amount;

        return $amount;
    }

    public function getCurrency(): string
    {
        return self::class;
    }

    public function acceptsCurrency(PaymentCurrency $currency): bool
    {
        return get_class($currency) === $this->getCurrency();
    }
}