<?php

namespace Jacek\App\Exchange\Domain\Transaction;

enum TransactionType
{
    case BUY;
    case SELL;
    case CHECK;
}
