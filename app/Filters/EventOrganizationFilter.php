<?php

namespace App\Filters;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class EventOrganizationFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('team.organization', function ($query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%');
        });
    }
}
