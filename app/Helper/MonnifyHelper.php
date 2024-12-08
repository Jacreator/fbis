<?php

namespace App\Helper;

use Exception;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Http;

class MonnifyHelper
{
    public function authCredentials(string $token): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$token,
        ];
    }

    public function loginAuth(): string
    {
        try {
            $response = Http::withBasicAuth(
                config('services.monnify.api_key'),
                config('services.monnify.secret_key')
            )
                ->post(config('services.monnify.url').'/api/v1/auth/login');
            $response = $response->json();

            return $response['responseBody']['accessToken'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function verifyTransaction($transactionRef)
    {
        try {
            $login = $this->loginAuth();
            $transaction = Http::withHeaders($this->authCredentials($login))
                ->get(config('services.monnify.url').'/api/v2/transactions/'.rawurlencode($transactionRef));
            
            if (! $transaction->successful()) {
                return [
                    'code' => HttpResponse::HTTP_NOT_FOUND,
                    'message' => 'transaction not found. please contact our customer service!',
                ];
            }

            return [
                'code' => HttpResponse::HTTP_OK,
                'message' => 'money received',
                'data' => json_decode($transaction->body()),
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function verifyTransferStatus($transactionRef)
    {
        try {
            $login = $this->loginAuth();

            $transaction = Http::withHeaders($this->authCredentials($login['accessToken']))
                ->get(config('services.monnify.url')."/api/v2/disbursements/single/summary?reference={$transactionRef}");

            if (! $transaction->successful()) {
                return [
                    'code' => HttpResponse::HTTP_NOT_FOUND,
                    'message' => 'transaction not found. please contact our customer service!',
                ];
            }

            return [
                'code' => HttpResponse::HTTP_OK,
                'message' => 'money received',
                'data' => json_decode($transaction->body()),
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
