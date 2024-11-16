<?php

namespace CoffeeMachine\Contracts\Buttons;

use CoffeeMachine\Contracts\Consumables\ConsumableItem;
use CoffeeMachine\Types\ButtonType;

interface MachineButton
{
    /**
     * Gets the generated button ID.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Gets the defined button name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Gets the defined button type.
     *
     * @return ButtonType
     */
    public function getType(): ButtonType;

    /**
     * Gets the related consumable item.
     *
     * @return null|class-string<ConsumableItem>
     */
    public function getConsumable(): ?string;
}