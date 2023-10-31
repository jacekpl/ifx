<?php

namespace Jacek\App\Exchange\Application;

use Jacek\App\Exchange\Domain\Money\Currency;
use Jacek\App\Exchange\Domain\Money\Money;
use Jacek\App\Exchange\Domain\Rate\RateRepositoryInterface;
use Jacek\App\Exchange\Domain\Rate\Rates;
use Jacek\App\Exchange\Domain\Transaction\TransactionType;

readonly class Exchange
{
    const COMMISSION = 0.01;

    public function __construct(private RateRepositoryInterface $rateRepository)
    {
    }

    public function exchange(float $amount, Currency $from, Currency $to, TransactionType $type): Money
    {
        $rate = $this->rateRepository->rate($from, $to);

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
