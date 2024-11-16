<?php

namespace CoffeeMachine\Support\Currency;

use CoffeeMachine\Common\Payments\BasePaymentCurrency;

final class Coin extends BasePaymentCurrency
{

    public function getSingularName(): string
    {
        return 'Coin';
    }

    public function getPluralName(): string
    {
        return 'Coins';
    }
}