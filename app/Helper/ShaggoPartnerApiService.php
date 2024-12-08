<?php

namespace App\Helper;

use Exception;
use App\Contract\AirtimeBillingServiceInterface;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Http;

class ShaggoPartnerApiService implements AirtimeBillingServiceInterface
{
  public function airtimeBilling($data)
  {
    try {
      $billed = Http::withHeaders([
        'Content-Type' => 'application/json',
        'hashKey' => config('services.shago.key'),
      ])
        ->post(config('services.shago.url') . '/b2b', [
          "serviceCode" => "QAB",
          "phone" => $data['phone_number'],
          "amount" => $data['amount'],
          "vend_type" => "VTU",
          "network" => $data['network'],
          "request_id" => "66666629227706"
        ]);

      if (!$billed->successful()) {
        return [
          'code' => HttpResponse::HTTP_NOT_FOUND,
          'message' => 'Third party not found. please contact our customer service!',
        ];
      }

      return [
        'code' => HttpResponse::HTTP_OK,
        'message' => 'money received',
        'data' => json_decode($billed->body()),
      ];
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function getCompanyBalance($data)
  {
    try {
      $companyBalance = Http::withHeaders([
        'Content-Type' => 'application/json',
        'hashKey' => config('services.shago.key'),
      ])
        ->post(config('services.shago.url') . '/b2b', [
          "serviceCode" => "BAL"
        ]);

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
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function commission(array $data) {
    $amount = $data['amount'];
    $commissionRate = 0.03; // 3%

    $commissionAmount = $amount * $commissionRate;
    $finalAmount = $amount - $commissionAmount;

    return $finalAmount;
  }
}
