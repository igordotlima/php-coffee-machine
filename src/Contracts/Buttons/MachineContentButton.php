<?php

namespace CoffeeMachine\Contracts\Buttons;

use CoffeeMachine\Contracts\Consumables\ContentItem;

interface MachineContentButton extends MachineButton
{
    /**
     * Gets the related content item to manage.
     *
     * @return class-string<ContentItem>
     */
    public function getContent(): string;
}