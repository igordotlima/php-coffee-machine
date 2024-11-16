<?php

namespace CoffeeMachine\Contracts\Consumables;

use CoffeeMachine\Exceptions\Consumables\InsufficientStockException;

interface ConsumableItem
{

    /**
     * The consumable option name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * The consumable option price.
     *
     * @return int
     */
    public function getPrice(): int;

    /**
     * The consumable contents. (e.g.: Sugar, Milk, Washer Fluid)
     *
     * @return ContentItem[]
     */
    public function getContents(): array;

    /**
     * Adds a content item to the consumable.
     *
     * @param ContentItem $item
     *
     * @return void
     */
    public function addContentItem(ContentItem $item): void;

    /**
     * Clear the consumable contents.
     *
     * @return void
     */
    public function clearContents(): void;

    /**
     * The consumable stock quantity.
     *
     * @return int
     */
    public function getStockQuantity(): int;

    /**
     * Determine if the consumable is out of stock.
     *
     * @return bool     True if the quantity equals zero.
     */
    public function isOutOfStock(): bool;

    /**
     * Consumes the item and decreases the stock quantity.
     *
     * @throws InsufficientStockException   If the item is out of stock.
     *
     * @return void
     */
    public function consume(): void;
}