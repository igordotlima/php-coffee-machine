<?php

namespace CoffeeMachine\Parts\Slots;

use CoffeeMachine\Common\Payments\BasePaymentSlot;
use CoffeeMachine\Support\Currency\Coin;

final class CoinPaymentSlot extends BasePaymentSlot
{

    public function getCurrency(): string
    {
        return Coin::class;
    }
}