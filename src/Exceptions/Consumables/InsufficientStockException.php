<?php

namespace CoffeeMachine\Exceptions\Consumables;

use CoffeeMachine\Contracts\Consumables\ConsumableItem;
use CoffeeMachine\Contracts\Consumables\ContentItem;
use Exception;

class InsufficientStockException extends Exception
{
    public function __construct(ConsumableItem|ContentItem $item)
    {
        parent::__construct(
            message: sprintf(
                "The item '%s' is out of stock.",
                $item->getName()
            ),
        );
    }
}