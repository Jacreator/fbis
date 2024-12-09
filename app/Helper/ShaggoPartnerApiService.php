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
      // $billed = Http::withHeaders([
      //   'Content-Type' => 'application/json',
      //   'hashKey' => config('services.shago.key'),
      // ])
      //   ->post(config('services.shago.url') . '/b2b', [
      //     "serviceCode" => "QAB",
      //     "phone" => $data['phone_number'],
      //     "amount" => $data['amount'],
      //     "vend_type" => "VTU",
      //     "network" => $data['network'],
      //     "request_id" => "66666629227706"
      //   ]);


      // if (!$billed->successful()) {
      //   return [
      //     'code' => HttpResponse::HTTP_NOT_FOUND,
      //     'message' => 'Third party not found. please contact our customer service!',
      //   ];
      // }
      $billed = '{
        "message": "transaction successful",
        "status": "200",
        "amount": 50,
        "transId": "1595596779728",
        "type": "VTU",
        "date": "2020-07-24 14:19:41",
        "phone": "07035666498"
      }';

      $billedResponse = json_decode($billed, true);
      $formattedResponse = json_encode([
        "trans_id" => 'SHA|'.$billedResponse['transId'],
        "trans_date" => $billedResponse['date']
      ]);
      return [
        'code' => HttpResponse::HTTP_OK,
        'message' => 'mart billed successfully',
        'data' => json_decode($formattedResponse),
      ];
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function getCompanyBalance($data)
  {
    try {
      // $companyBalance = Http::withHeaders([
      //   'Content-Type' => 'application/json',
      //   'hashKey' => config('services.shago.key'),
      // ])
      // ->post(config('services.shago.url') . '/b2b', [
      //   "serviceCode" => "BAL"
      // ]);

      // if (!$companyBalance->successful()) {
      //   return [
      //     'code' => HttpResponse::HTTP_NOT_FOUND,
      //     'message' => 'Third party not found. please contact our customer service!',
      //   ];
      // }
      $companyBalance = '{
        "status": "200",
        "message": "Successful",
        "wallet": {
          "WalletID": "34846368",
          "primaryBalance": "999994292140.90",
          "commissionBalance": "193748.57",
          "created_at": {
            "date": "2019-09-24 14:22:10.000000",
            "timezone_type": 3,
            "timezone": "UTC"
          }
        }
      }';

      $response = json_decode($companyBalance, true);
      $primaryBalance = $response['wallet']['primaryBalance'];
      $formattedResponse = json_encode([
        "primaryBalance" => $primaryBalance
      ]);
      return [
        'code' => HttpResponse::HTTP_OK,
        'message' => 'wallet information received',
        'data' => json_decode($formattedResponse),
      ];
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function commission(array $data)
  {
    $amount = $data['amount'];
    $commissionRate = 0.03; // 3%

    $commissionAmount = $amount * $commissionRate;
    $finalAmount = $amount - $commissionAmount;

    return $finalAmount;
  }
}
