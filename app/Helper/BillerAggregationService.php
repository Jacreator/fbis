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
        "agentReference" => $data['trans_ref']
      ]);

    if (! $billed->successful()) {
      return [
        'code' => HttpResponse::HTTP_NOT_FOUND,
        'message' => 'Third party not found. please contact our customer service!',
      ];
    }
    $billedResponse = json_decode($billed, true);
    $formattedResponse = json_encode([
      "trans_id" => 'BXN|' . $billedResponse['data']['baxiReference'],
      "trans_date" => now()
    ]);
    return [
      'code' => HttpResponse::HTTP_OK,
      'message' => 'wallet information received',
      'data' => json_decode($formattedResponse),
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
    $companyBalanceResponse = json_decode($companyBalance->body(), true);
    $formattedResponse = json_encode([
      "primaryBalance" => $companyBalanceResponse['data']['balance']
    ]);
    return [
      'code' => HttpResponse::HTTP_OK,
      'message' => 'wallet information received',
      'data' => json_decode($formattedResponse),
    ];
  }

  public function commission(array $data)
  {
    $amount = $data['amount'];
    $type = $data['type'];
    $commissionRate = 0;
    if ($type == 'airtime') {
      $commissionRate = 0.05; // 5%
    }

    $commissionAmount = $amount * $commissionRate;
    $finalAmount = $amount - $commissionAmount;

    return $finalAmount;
  }
}
