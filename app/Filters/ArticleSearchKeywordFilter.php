<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ArticleSearchKeywordFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function (Builder $query) use ($value) {
            $query->where('title', 'like', "%{$value}%")
                  ->orWhere('body', 'like', "%{$value}%")
                  ->orWhereHas('user', function (Builder $query) use ($value) {
                      $query->where('contributor_name', 'like', "%{$value}%");
                  });
        });
    }
}
