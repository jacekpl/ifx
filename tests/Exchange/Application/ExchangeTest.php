<?php

namespace Exchange\Application;

use Jacek\App\Exchange\Application\Exchange;
use Jacek\App\Exchange\Domain\Money\Currency;
use Jacek\App\Exchange\Domain\Rate\Rate;
use Jacek\App\Exchange\Domain\Rate\RateRepositoryInterface;
use Jacek\App\Exchange\Domain\Rate\Rates;
use Jacek\App\Exchange\Domain\Transaction\TransactionType;
use Jacek\App\Exchange\Infrastructure\InMemoryRateRepository;
use PHPUnit\Framework\TestCase;

class ExchangeTest extends TestCase
{
    private RateRepositoryInterface $rateRepository;

    protected function setUp(): void
    {
        $rates = new Rates();
        $rates->add(new Rate(new Currency('EUR'), new Currency('GBP'), 1.5678));
        $rates->add(new Rate(new Currency('GBP'), new Currency('EUR'), 1.5432));
        $this->rateRepository = new InMemoryRateRepository();
        $this->rateRepository->setup($rates);
    }

    public function testExchange()
    {
        $exchange = new Exchange($this->rateRepository);
        $this->assertEquals(156.78, $exchange->exchange(100, new Currency('EUR'), new Currency('GBP'), TransactionType::CHECK)->amount());
        $this->assertEquals(154.32, $exchange->exchange(100, new Currency('GBP'), new Currency('EUR'), TransactionType::CHECK)->amount());
    }

    public function testMissingRateException()
    {
        $exchange = new Exchange($this->rateRepository);
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Rate not found');
        $exchange->exchange(100, new Currency('GBP'), new Currency('USD'), TransactionType::CHECK);
    }

    public function testSell100EURforGBP()
    {
        $exchange = new Exchange($this->rateRepository);
        $this->assertEquals(155.2122, $exchange->exchange(100, new Currency('EUR'), new Currency('GBP'), TransactionType::SELL)->amount());
    }

    public function testSell100GBPforEUR()
    {
        $exchange = new Exchange($this->rateRepository);
        $this->assertEquals(152.7768, $exchange->exchange(100, new Currency('GBP'), new Currency('EUR'), TransactionType::SELL)->amount());
    }

    public function testBuy100GBPforEUR()
    {
        $exchange = new Exchange($this->rateRepository);
        $this->assertEquals(155.2122, $exchange->exchange(100, new Currency('EUR'), new Currency('GBP'), TransactionType::BUY)->amount());
    }

    public function testBuy100EURforGBP()
    {
        $exchange = new Exchange($this->rateRepository);
        $this->assertEquals(152.7768, $exchange->exchange(100, new Currency('GBP'), new Currency('EUR'), TransactionType::BUY)->amount());
    }
}
