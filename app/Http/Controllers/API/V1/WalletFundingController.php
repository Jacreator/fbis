<?php

namespace App\Http\Controllers\API\V1;

use App\Exceptions\Transaction\DuplicateTransactionException;
use App\Exceptions\Transaction\InvalidTransactionAmountException;
use App\Exceptions\Transaction\UnrecognizedTransactionException;
use App\Exceptions\Wallet\WalletNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\TopUpWalletRequest;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Throwable;

class WalletFundingController extends Controller
{
    public function __construct(protected WalletService $walletService)
    {

    }

    public function __invoke(TopUpWalletRequest $request): JsonResponse
    {
        try {
            $wallet = $this->walletService->fund($request->validated());

            return $this->ok('Wallet topped up successfully', $wallet);
        } catch (
            WalletNotFoundException|DuplicateTransactionException|
            UnrecognizedTransactionException|InvalidTransactionAmountException $e
        ) {
            report($e);
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Throwable $e) {
            report($e);
            return $this->error($e->getMessage());
        }
    }
}
