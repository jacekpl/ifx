<?php

namespace Jacek\App\Exchange\Domain\Rate;

use Jacek\App\Exchange\Domain\Money\Currency;

class Rates
{
    public function __construct(
        /** @var Rate[] */
        private array $rates = []
    )
    {
    }

    public function add(Rate $rate): void
    {
        $this->rates[] = $rate;
    }

    public function rate(Currency $from, Currency $to): Rate
    {
        foreach ($this->rates as $rate) {
            if ($rate->fromCurrency()->equals($from) && $rate->toCurrency()->equals($to)) {
                return $rate;
            }
        }
        throw new \DomainException('Rate not found');
    }
}
