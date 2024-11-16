<?php

namespace CoffeeMachine\Parts\Buttons;

use CoffeeMachine\Common\Parts\Buttons\BaseButton;
use CoffeeMachine\Types\ButtonType;

class RefundButton extends BaseButton
{

    public function getName(): string
    {
        return 'Refund';
    }

    public function getType(): ButtonType
    {
        return ButtonType::TYPE_REFUND;
    }
}