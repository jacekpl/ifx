<?php

namespace Jacek\App\Exchange\Domain\Rate;

use Jacek\App\Exchange\Domain\Money\Currency;

interface RateRepositoryInterface
{
    public function setup(Rates $rates): void;
    public function add(Rate $rate): void;
    public function rate(Currency $from, Currency $to): Rate;
}