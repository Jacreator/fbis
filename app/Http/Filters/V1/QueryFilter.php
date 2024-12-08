<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    protected Builder $builder;

    protected Request $request;

    protected array $sortable = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function sort($value): void
    {
        $sortAttributes = explode(',', $value);

        foreach ($sortAttributes as $sortAttribute) {
            $direction = str_starts_with($sortAttribute, '-') ? 'desc' : 'asc';
            $column = Str::of($sortAttribute)->remove('-')->snake()->value();

            if (in_array($column, $this->sortable)) {
                $this->builder->orderBy($column, $direction);
            }
        }
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        $this->iterateAndCallMethod($this->request->all());

        return $builder;
    }

    public function iterateAndCallMethod(array $array): void
    {
        foreach ($array as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
