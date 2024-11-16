<?php

namespace CoffeeMachine\Contracts\Payment;

use InvalidArgumentException;

// TODO: It would be nice to add allowed denominations since in real life you have those constraints.
interface PaymentSlot
{
    /**
     * Defines the accepted payment currency class.
     *
     * This method is expected to be overridden by each payment slot.
     *
     * @return class-string
     */
    public function getCurrency(): string;

    /**
     * Determines if the payment slot accepts a currency.
     *
     * @param PaymentCurrency $currency
     * @return bool     True if the slot accepts the currency.
     */
    public function acceptsCurrency(PaymentCurrency $currency): bool;

    /**
     * Get the amount inserted in the slot.
     *
     * @return int  The total amount.
     */
    public function getPendingAmount(): int;

    /**
     * Get the amount captured in the slot.
     *
     * @return int  The captured amount.
     */
    public function getCapturedAmount(): int;

    /**
     * Insert a currency medium in the slot.
     *
     * @param PaymentCurrency $currency
     *
     * @return void
     */
    public function insert(PaymentCurrency $currency): void;

    /**
     * Captures the inserted amount into the vault.
     *
     * @param int $amount The amount to capture.
     *
     * @return int                              The amount captured into the vault.
     * @throws InvalidArgumentException         If the specified amount is higher than the available amount.
     */
    public function capture(int $amount): int;

    /**
     * Refund the current currency amount in the slot.
     *
     * @return int  The refunded amount.
     */
    public function refund(): int;

    /**
     * Withdraw the specified amount from the slot.
     *
     * @param int $amount The amount to withdraw.
     *
     * @return int  The amount withdrawn.
     */
    public function withdraw(int $amount): int;
}