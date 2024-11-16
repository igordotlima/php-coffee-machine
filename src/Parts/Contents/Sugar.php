<?php

namespace CoffeeMachine\Parts\Contents;

use CoffeeMachine\Common\Consumables\BaseContentItem;

class Sugar extends BaseContentItem
{

    public function getName(): string
    {
        return 'Sugar';
    }

    public function getMaximumQuantity(): int
    {
        return 4;
    }
}