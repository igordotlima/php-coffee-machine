<?php

namespace CoffeeMachine\Parts\Slots;

use CoffeeMachine\Common\Payments\BasePaymentSlot;
use CoffeeMachine\Support\Currency\Banknote;

final class BanknotePaymentSlot extends BasePaymentSlot
{

    public function getCurrency(): string
    {
        return Banknote::class;
    }
}