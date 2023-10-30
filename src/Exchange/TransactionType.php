<?php

namespace Jacek\App\Exchange;

enum TransactionType
{
    case BUY;
    case SELL;
    case CHECK;
}
