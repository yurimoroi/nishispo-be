<?php

namespace App\Filters;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class GroupwarePrivilegesFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $values = is_array($value) ? $value : explode(',', $value);

        $query->whereHas('team.groupwarePrivileges', function ($query) use ($values) {
            $query->whereIn('payment_flg', $values);
        });
    }
}
