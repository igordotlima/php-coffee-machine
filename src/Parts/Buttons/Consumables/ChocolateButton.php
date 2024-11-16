<?php

namespace CoffeeMachine\Parts\Buttons\Consumables;

use CoffeeMachine\Common\Parts\Buttons\BaseButton;
use CoffeeMachine\Parts\Consumables\Tea;
use CoffeeMachine\Types\ButtonType;

class ChocolateButton extends BaseButton
{
    public function getName(): string
    {
        return 'Coffee';
    }

    public function getType(): ButtonType
    {
        return ButtonType::TYPE_CONSUMABLE;
    }
    
    public function getConsumable(): string
    {
        return Tea::class;
    }
}