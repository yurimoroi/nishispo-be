<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class EventSearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $fieldToSearch = [
            'name',
            'description'
        ];

        $query->where(function ($q) use ($fieldToSearch, $value) {
            array_map(function ($field) use ($q, $value) {
                $q->orWhere($field, 'LIKE', '%' . $value . '%');
            }, $fieldToSearch);
        });
        
    }
}
