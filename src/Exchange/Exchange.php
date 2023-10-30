<?php

namespace Jacek\App\Exchange;

use Jacek\App\Money\Currency;
use Jacek\App\Money\Money;

readonly class Exchange
{
    const COMMISSION = 0.01;

    public function __construct(private Rates $rates)
    {
    }

    public function exchange(float $amount, Currency $from, Currency $to, TransactionType $type): Money
    {
        $rate = $this->rates->rate($from, $to);

        if($type === TransactionType::BUY) {
            $amount = bcmul($amount, (1 - self::COMMISSION), 4);
        }

        $money = new Money(bcmul($amount, $rate->rate(), 4), $to);

        if ($type === TransactionType::SELL) {
            return new Money(bcmul($money->amount(), (1 - self::COMMISSION), 4), $to);
        }

        return $money;
    }
}