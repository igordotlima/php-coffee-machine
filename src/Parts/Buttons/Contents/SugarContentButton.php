<?php

namespace CoffeeMachine\Parts\Buttons\Contents;

use CoffeeMachine\Common\Parts\Buttons\BaseButton;
use CoffeeMachine\Contracts\Buttons\MachineContentButton;
use CoffeeMachine\Parts\Contents\Sugar;
use CoffeeMachine\Types\ButtonType;

class SugarContentButton extends BaseButton implements MachineContentButton
{
    public function getName(): string
    {
        return 'Sugar';
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
        return Sugar::class;
    }
}