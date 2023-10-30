<?php

namespace Jacek\App\Exchange;

use Jacek\App\Money\Currency;

readonly class Rate
{
    public function __construct(
        private Currency $fromCurrency,
        private Currency $toCurrency,
        private float $rate
    )
    {
    }

    public function fromCurrency(): Currency
    {
        return $this->fromCurrency;
    }

    public function toCurrency(): Currency
    {
        return $this->toCurrency;
    }

    public function rate(): float
    {
        return $this->rate;
    }
}