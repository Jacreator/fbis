<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;

class TransactionFilter extends QueryFilter
{
    protected array $sortable = [
        'name',
        'created_at',
        'updated_at',
    ];

    public function transaction_type($value): Builder
    {
        return $this->builder->where('transaction_type',$value);
    }
}
