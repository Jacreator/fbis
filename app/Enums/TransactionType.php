<?php

declare(strict_types=1);

namespace App\Enums;

enum TransactionType: string
{
    case ACCOUNT_CREDIT= 'account_credit';
    case ACCOUNT_DEBIT= 'account_debit';

    case AIRTIME_PURCHASE = 'airtime_purchase';

    case STATUS_COMPLETED = 'completed';
    case STATUS_PENDING = 'pending';
    case STATUS_FAILED = 'failed';
}
