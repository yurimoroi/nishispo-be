<?php

namespace App\Filters;

use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use DB;

class EventExpiringSoonFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value) {
            if ($value == true) {
                $query->whereBetween('ended_at', [Carbon::now()->subDays(21), Carbon::now()]);
            }
        }
    }
}
