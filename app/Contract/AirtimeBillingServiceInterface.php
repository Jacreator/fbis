<?php

declare(strict_types=1);

namespace App\Contract;

interface AirtimeBillingServiceInterface
{
  public function airtimeBilling($data);

  public function getCompanyBalance($data);

  public function commission(array $data);

}