<?php

namespace App\Filters;

use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ArticleDatePublishFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value) {
            
            [$date1, $date2] = $value;

            $start_date = Carbon::parse($date1)->startOfDay();
            $end_date = Carbon::parse(isset($date2) ? $date2 : $date1)->endOfDay();

            $query->whereBetween('published_at', [$start_date, $end_date]);
            
        }
    }
}
