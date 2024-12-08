<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TransactionFilter;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Throwable;

class TransactionController extends Controller
{
    public function __construct(protected TransactionService $transactionService)
    {}

    public function index(TransactionFilter $filters): JsonResponse
    {
        try {
            $transactions = $this->transactionService->fetchAll($filters);

            return $this->ok('All transactions fetched successfully', TransactionResource::collection($transactions));
        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    public function show(Transaction $transaction): JsonResponse
    {
        try {
            return $this->ok('Transaction fetched successfully', new TransactionResource($transaction));
        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }
    }
}
