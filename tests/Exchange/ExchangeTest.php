<?php

namespace Jacek\App\Tests\Exchange;

use Jacek\App\Exchange\Exchange;
use Jacek\App\Exchange\Rate;
use Jacek\App\Exchange\Rates;
use Jacek\App\Exchange\TransactionType;
use Jacek\App\Money\Currency;
use PHPUnit\Framework\TestCase;

class ExchangeTest extends TestCase
{
    public function testExchange()
    {
        $rates = new Rates();
        $rates->add(new Rate(new Currency('EUR'), new Currency('GBP'), 1.5678));
        $rates->add(new Rate(new Currency('GBP'), new Currency('EUR'), 1.5432));

        $exchange = new Exchange($rates);
        $this->assertEquals(156.78, $exchange->exchange(100, new Currency('EUR'), new Currency('GBP'), TransactionType::CHECK)->amount());
        $this->assertEquals(154.32, $exchange->exchange(100, new Currency('GBP'), new Currency('EUR'), TransactionType::CHECK)->amount());
    }

    public function testMissingRateException()
    {
        $rates = new Rates();
        $rates->add(new Rate(new Currency('EUR'), new Currency('GBP'), 1.5678));

        $exchange = new Exchange($rates);
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Rate not found');
        $exchange->exchange(100, new Currency('GBP'), new Currency('USD'), TransactionType::CHECK);
    }

    public function testSell100EURforGBP()
    {
        $rates = new Rates();
        $rates->add(new Rate(new Currency('EUR'), new Currency('GBP'), 1.5678));
        $rates->add(new Rate(new Currency('GBP'), new Currency('EUR'), 1.5432));

        $exchange = new Exchange($rates);
        $this->assertEquals(155.2122, $exchange->exchange(100, new Currency('EUR'), new Currency('GBP'), TransactionType::SELL)->amount());
    }

    public function testSell100GBPforEUR()
    {
        $rates = new Rates();
        $rates->add(new Rate(new Currency('EUR'), new Currency('GBP'), 1.5678));
        $rates->add(new Rate(new Currency('GBP'), new Currency('EUR'), 1.5432));

        $exchange = new Exchange($rates);
        $this->assertEquals(152.7768, $exchange->exchange(100, new Currency('GBP'), new Currency('EUR'), TransactionType::SELL)->amount());
    }

    public function testBuy100GBPforEUR()
    {
        $rates = new Rates();
        $rates->add(new Rate(new Currency('EUR'), new Currency('GBP'), 1.5678));
        $rates->add(new Rate(new Currency('GBP'), new Currency('EUR'), 1.5432));

        $exchange = new Exchange($rates);
        $this->assertEquals(155.2122, $exchange->exchange(100, new Currency('EUR'), new Currency('GBP'), TransactionType::BUY)->amount());
    }

    public function testBuy100EURforGBP()
    {
        $rates = new Rates();
        $rates->add(new Rate(new Currency('EUR'), new Currency('GBP'), 1.5678));
        $rates->add(new Rate(new Currency('GBP'), new Currency('EUR'), 1.5432));

        $exchange = new Exchange($rates);
        $this->assertEquals(152.7768, $exchange->exchange(100, new Currency('GBP'), new Currency('EUR'), TransactionType::BUY)->amount());
    }
}