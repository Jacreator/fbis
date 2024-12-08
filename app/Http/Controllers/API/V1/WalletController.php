<?php

namespace App\Http\Controllers\API\V1;

use App\Exceptions\Wallet\WalletAlreadyExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\CreateWalletRequest;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Throwable;

class WalletController extends Controller
{
    public function __construct(protected WalletService $walletService)
    {

    }

    public function create(CreateWalletRequest $request): JsonResponse
    {
        try {
            $wallet = $this->walletService->createWallet($request->validated());

            return $this->ok('User wallet created successfully', new WalletResource($wallet));
        } catch (WalletAlreadyExistsException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    public function show(Wallet $wallet): JsonResponse
    {
        try {
            return $this->ok('User wallet fetched successfully', $wallet);
        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }
    }
}
