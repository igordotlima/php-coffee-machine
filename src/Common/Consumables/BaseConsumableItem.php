<?php

namespace CoffeeMachine\Common\Consumables;

use CoffeeMachine\Contracts\Consumables\ConsumableItem;
use CoffeeMachine\Contracts\Consumables\ContentItem;
use CoffeeMachine\Exceptions\Consumables\InsufficientStockException;

abstract class BaseConsumableItem implements ConsumableItem
{
    /**
     * The consumable contents.
     *
     * @var array
     */
    private array $contents = [];

    /**
     * The available stock quantity.
     *
     * @var int
     */
    private int $quantity = 0;

    public function __construct(int $quantity = 1)
    {
        $this->quantity = $quantity;
    }

    public function getContents(): array
    {
        return $this->contents;
    }

    public function addContentItem(ContentItem $item): void
    {
        $this->contents[] = $item;
    }

    public function clearContents(): void
    {
        $this->contents = [];
    }


    public function getStockQuantity(): int
    {
        return $this->quantity;
    }

    public function isOutOfStock(): bool
    {
        return $this->getStockQuantity() === 0;
    }

    public function consume(): void
    {
        if ($this->isOutOfStock()) {
            throw new InsufficientStockException($this);
        }

        $this->quantity -= 1;
    }
}