<?php

namespace CoffeeMachine\Parts\Consumables;

use CoffeeMachine\Common\Consumables\BaseConsumableItem;

class Tea extends BaseConsumableItem
{
    public function getName(): string
    {
        return 'Tea';
    }

    public function getPrice(): int
    {
        return 3;
    }
}