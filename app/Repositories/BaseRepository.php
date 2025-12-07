<?php

namespace App\Repositories;

use App\Models\Article;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseRepository
{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(
        $with = [],
        $withCount = [],
        $columns = ['*'],
        $orderBy = 'id',
        $sortBy = 'asc',
        $where = [],
        $scopes = [],
        $limit = null,
        $whereHas = [],
        $paginate = false,
        $perPage = 10,
        $allowedFilters = [],
        $prioritize = [],
        $toSql = false,
    ) {
        $query = QueryBuilder::for($this->model::class)
            ->select($columns)
            ->with($with)
            ->where($where);


        if (!empty($prioritize)) {
            $ids = implode(',', array_map(fn($id) => "'$id'", $prioritize));
            $query->orderByRaw("FIELD(id, $ids) DESC");
        } else {
            $query->orderBy($orderBy, $sortBy);
        }

        if (!empty($withCount)) {
            foreach ($withCount as $relation => $closure) {
                if (is_callable($closure)) {
                    $query->withCount([$relation => $closure]);
                } else {
                    $query->withCount($relation);
                }
            }
        }

        if (!empty($allowedFilters)) {
            $query->allowedFilters($allowedFilters);
        }

        if (!empty($scopes)) {
            foreach ($scopes as $scope) {
                if (method_exists($this->model, 'scope' . ucfirst($scope))) {
                    $query->{$scope}();
                }
            }
        }

        if (!empty($whereHas)) {
            foreach ($whereHas as $relation => $conditions) {
                $query->whereHas($relation, function ($q) use ($conditions) {
                    foreach ($conditions as $column => $value) {
                        if ($column === 'scopes' && is_array($value)) {
                            foreach ($value as $scope) {
                                if (is_callable([$q, $scope])) {
                                    $q->{$scope}();
                                }
                            }
                        } elseif (is_array($value)) {
                            $q->where($column, $value[0], $value[1]);
                        } else {
                            $q->where($column, $value);
                        }
                    }
                });
            }
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        if ($toSql) {
            return $query->toSql();
        }

        if ($paginate) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function findOnColumn($column = false, $value = "", $with = [])
    {
        if ($column && empty($column)) {
            throw new Exception("Column should not be empty");
        }

        return $this->model->with($with)->where($column, $value)->first();
    }

    public function find(
        string $id,
        $with = [],
        $columns = ['*'],
        $where = [],
        $scopes = [],
        $withCount = []
    ) {
        $query = $this->model
            ->select($columns)
            ->with($with)
            ->where($where);

        if (!empty($withCount)) {
            foreach ($withCount as $relation => $closure) {
                if (is_callable($closure)) {
                    $query->withCount([$relation => $closure]);
                } else {
                    $query->withCount($relation);
                }
            }
        }

        if (!empty($scopes)) {
            foreach ($scopes as $scope) {
                if (method_exists($this->model, 'scope' . ucfirst($scope))) {
                    $query->{$scope}();
                }
            }
        }

        return $query->find($id);
    }


    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data)
    {
        $record = $this->model->find($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return false;
    }

    public function delete(string $id)
    {
        $record = $this->model->find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
