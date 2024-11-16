<?php

namespace CoffeeMachine\Support\Currency;

use CoffeeMachine\Common\Payments\BasePaymentCurrency;

final class Banknote extends BasePaymentCurrency
{

    public function getSingularName(): string
    {
        return 'Banknote';
    }

    public function getPluralName(): string
    {
        return 'Banknotes';
    }
}