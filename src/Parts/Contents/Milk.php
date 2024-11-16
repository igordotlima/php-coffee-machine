<?php

namespace CoffeeMachine\Parts\Contents;

use CoffeeMachine\Common\Consumables\BaseContentItem;

class Milk extends BaseContentItem
{

    public function getName(): string
    {
        return 'Milk';
    }

    public function getMaximumQuantity(): int
    {
        return 4;
    }
}