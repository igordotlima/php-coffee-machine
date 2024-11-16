<?php

namespace CoffeeMachine\Contracts\Consumables;

use CoffeeMachine\Exceptions\Consumables\InsufficientStockException;
use OutOfBoundsException;

interface ContentItem
{
    /**
     * The content item name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * The content stock quantity.
     *
     * @return int
     */
    public function getStockQuantity(): int;

    /**
     * Determine if the content is out of stock.
     *
     * @return bool     True if the quantity equals zero.
     */
    public function isOutOfStock(): bool;

    /**
     * Consumes the item and decreases the stock quantity.
     *
     * @throws InsufficientStockException   If the item is out of stock.
     * @return void
     */
    public function consume(): void;
}