<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contract\Filterable;
use App\Http\Filters\V1\QueryFilter;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    private Model $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    abstract public function getModelClass(): Model;

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(int $id, array $attributes): Model
    {
        $model = $this->find($id);
        $model->update($attributes);

        return $model->fresh();
    }

    public function delete(int $id): bool
    {
        $model = $this->find($id);
        $model->delete();

        return true;
    }

    public function findByWhere($column, $value, $all = false): Model|Collection|null
    {
        $query = $this->model->where($column, $value);

        if (! $all) {
            return $query->first();
        }

        return $query->all();
    }

    public function count($key, $value)
    {
        return $this->model->where($key, $value)->count();
    }

    public function paginate($number)
    {
        return $this->model->paginate($number);
    }

    public function showFolder($key)
    {
        return $this->model
            ->whereJsonContains($key, auth()->user()->uuid)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function where(string $column, string $value): Builder
    {
        return $this->model->where($column, $value);
    }

    public function whereIn(string $column, array $values): Builder
    {
        return $this->model->whereIn($column, $values);
    }

    public function updateMany(string $column, array $values, array $attributes): int
    {
        return $this->model->whereIn($column, $values)
            ->update($attributes);
    }

    public function exists(array $attributes): bool
    {
        return $this->model->where($attributes)->exists();
    }

    /**
     * @throws Exception
     */
    public function setFilters(QueryFilter $filters)
    {
        if(! $this->model instanceof Filterable) {
            throw new  Exception('You need to implement Filterable Contract on the model you are filtering!');
        }

        return $this->model->filter($filters);
    }

    /**
     * @throws Exception
     */
    public function getAllWithFilters(QueryFilter $filters, array $with = [])
    {
        return $this->setFilters($filters)
            ->with($with)
            ->get();
    }
}
