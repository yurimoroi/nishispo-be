<?php

namespace App\Filters;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class GroupwarePrivilegesUnpaidFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if($value == true)
        {
            $query->whereHas('team.groupwarePrivileges', function ($query) {
                $query->where('payment_flg', 0);  // Filter payment_flg == 0
            });
        }
    }
}
