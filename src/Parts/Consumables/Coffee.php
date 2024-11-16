<?php

namespace CoffeeMachine\Parts\Consumables;

use CoffeeMachine\Common\Consumables\BaseConsumableItem;

class Coffee extends BaseConsumableItem
{
    public function getName(): string
    {
        return 'Coffee';
    }

    public function getPrice(): int
    {
        return 2;
    }
}