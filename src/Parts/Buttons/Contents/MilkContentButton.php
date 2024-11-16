<?php

namespace CoffeeMachine\Parts\Buttons\Contents;

use CoffeeMachine\Common\Parts\Buttons\BaseButton;
use CoffeeMachine\Contracts\Buttons\MachineContentButton;
use CoffeeMachine\Parts\Contents\Milk;
use CoffeeMachine\Types\ButtonType;

class MilkContentButton extends BaseButton implements MachineContentButton
{
    public function getName(): string
    {
        return 'Milk';
    }

    /**
     * @inheritDoc
     */
    public function getType(): ButtonType
    {
        return ButtonType::TYPE_CONTENT;
    }

    public function getContent(): string
    {
        return Milk::class;
    }
}