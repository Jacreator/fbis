<?php

namespace App\Services;

use App\Http\Filters\V1\QueryFilter;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Exception;

class TransactionService extends BaseService
{
    public function __construct(protected TransactionRepository $transactionRepository)
    {}

    /**
     * @throws Exception
     */
    public function fetchAll(QueryFilter $filter, array $with = [])
    {
        $paginatedResults = $this->transactionRepository
            ->getAllWithFilters($filter);

        return $paginatedResults->groupBy('trans_date')
            ->flatMap(function ($group, $createdAt) {
                $formattedCreatedAt = Carbon::parse($createdAt)->format('F j, Y');

                return [$formattedCreatedAt => $group];
            })
            ->toArray();
    }
}
