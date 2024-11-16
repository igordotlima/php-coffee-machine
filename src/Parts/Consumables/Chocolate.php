<?php

namespace CoffeeMachine\Parts\Consumables;

use CoffeeMachine\Common\Consumables\BaseConsumableItem;

class Chocolate extends BaseConsumableItem
{
    public function getName(): string
    {
        return 'Chocolate';
    }

    public function getPrice(): int
    {
        return 5;
    }
}