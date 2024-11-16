<?php

namespace CoffeeMachine\Common\Parts\Buttons;

use CoffeeMachine\Contracts\Buttons\MachineButton;

abstract class BaseButton implements MachineButton
{

    public function getId(): string
    {
        return str_replace(' ', '.', strtolower($this->getName()));
    }

    public function getConsumable(): ?string
    {
        return null;
    }
}