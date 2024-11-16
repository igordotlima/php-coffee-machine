<?php

namespace CoffeeMachine\Common\Consumables;

use CoffeeMachine\Contracts\Consumables\ContentItem;
use OutOfBoundsException;

abstract class BaseContentItem implements ContentItem
{
    /**
     * The content item stock quantity.
     *
     * @var int
     */
    private int $quantity = 0;

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
        if ($this->quantity === 0) {
            throw new OutOfBoundsException(
                message: 'There are no more contents to consume.'
            );
        }

        $this->quantity -= 1;
    }
}