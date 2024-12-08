<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Filters\V1\QueryFilter;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class TransactionRepository extends BaseRepository
{
    public function getModelClass(): Model
    {
        return new Transaction();
    }

    /**
     * @throws \Exception
     */
    public function getAllWithFilters(QueryFilter $filters, array $with = [])
    {
        return $this->setFilters($filters)
            ->with($with)
            ->where('wallet_id', auth()->user()->wallet->wallet_id)
            ->get();
    }
}
