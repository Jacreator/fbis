<?php

namespace App\Helper;

use App\Contract\AirtimeBillingServiceInterface;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Http;

class BillerAggregationService implements AirtimeBillingServiceInterface
{
  public function airtimeBilling($data)
  {
    $billed = Http::withHeaders([
      'Content-Type' => 'application/json',
      'x-api-key' => config('services.biller.key'),
      'Accept' => 'application/json',
    ])
      ->post(config('services.biller.url') . '/airtime/request', [
        "phone" => $data['phone_number'],
        "amount" => $data['amount'],
        "service_type" => strtolower($data['network']),
        "plan" => "prepaid",
        "agentId" => config('services.biller.agentId'),
        "agentReference" => config('services.biller.agentReference')
      ]);

    if (! $billed->successful()) {
      return [
        'code' => HttpResponse::HTTP_NOT_FOUND,
        'message' => 'Third party not found. please contact our customer service!',
      ];
    }

    return [
      'code' => HttpResponse::HTTP_OK,
      'message' => 'wallet information received',
      'data' => json_decode($billed->body()),
    ];
  }

  public function getCompanyBalance($data)
  {
    $companyBalance = Http::withHeaders([
      'Content-Type' => 'application/json',
      'x-api-key' => config('services.biller.key'),
      'Accept' => 'application/json',
    ])
      ->get(config('services.biller.url') . '/superagent/account/balance');

    if (! $companyBalance->successful()) {
      return [
        'code' => HttpResponse::HTTP_NOT_FOUND,
        'message' => 'Third party not found. please contact our customer service!',
      ];
    }

    return [
      'code' => HttpResponse::HTTP_OK,
      'message' => 'wallet information received',
      'data' => json_decode($companyBalance->body()),
    ];
  }

  public function commission(array $data)
  {
    $amount = $data['amount'];
    $commissionRate = 0.05; // 5%

    $commissionAmount = $amount * $commissionRate;
    $finalAmount = $amount - $commissionAmount;

    return $finalAmount;
  }
}
