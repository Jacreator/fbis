<?php

declare(strict_types=1);

namespace App\Enums;

enum BillPaymentType: string
{
  case SHAGO = '';
  case BILLER = '';
}
