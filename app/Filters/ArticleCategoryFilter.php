<?php 
namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ArticleCategoryFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('categories', function (Builder $query) use ($value) {
            $query->whereIn('article_category_id', (array)$value);
        });
    }
}