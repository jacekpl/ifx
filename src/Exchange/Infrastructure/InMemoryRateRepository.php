<?php

namespace Jacek\App\Exchange\Infrastructure;

use Jacek\App\Exchange\Domain\Money\Currency;
use Jacek\App\Exchange\Domain\Rate\Rate;
use Jacek\App\Exchange\Domain\Rate\RateException;
use Jacek\App\Exchange\Domain\Rate\RateRepositoryInterface;
use Jacek\App\Exchange\Domain\Rate\Rates;

class InMemoryRateRepository implements RateRepositoryInterface
{
    public function __construct(private array $rates = [])
    {
    }

    public function setup(Rates $rates): void
    {
        $this->rates = $rates->all();
    }

    public function add(Rate $rate): void
    {
        array_map(function (Rate $current) use ($rate) {
            if ($current->fromCurrency()->equals($rate->fromCurrency()) && $current->toCurrency()->equals($rate->toCurrency())) {
                throw new RateException('Rate already exists');
            }
        }, $this->rates);

        $this->rates[] = $rate;
    }

    public function rate(Currency $from, Currency $to): Rate
    {
        foreach ($this->rates as $rate) {
            if ($rate->fromCurrency()->equals($from) && $rate->toCurrency()->equals($to)) {
                return $rate;
            }
        }

        throw new RateException('Rate not found');
    }
}