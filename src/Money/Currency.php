<?php

namespace Jacek\App\Money;

readonly class Currency
{
    private string $code;

    public function __construct(string $code)
    {
        if (strlen($code) !== 3) {
            throw new \DomainException('Currency must be 3 characters long');
        }

        $code = strtoupper($code);

        if (!preg_match('/^[A-Z]+$/', $code)) {
            throw new \DomainException('Currency must contain only uppercase letters');
        }

        $this->code = $code;
    }

    public function equals(Currency $other): bool
    {
        return $this->code === $other->code;
    }

    public function code(): string
    {
        return $this->code;
    }
}
