<?php

namespace CoffeeMachine\Types;

/**
 * The supported button types in the machine.
 */
enum ButtonType
{
    /**
     * Used for consumable items. (e.g.: Coffee)
     */
    case TYPE_CONSUMABLE;

    /**
     * Used to increase contents. (e.g.: Sugar)
     */
    case TYPE_CONTENT;

    /**
     * Used to request a refund for a payment slot.
     */
    case TYPE_REFUND;
}
