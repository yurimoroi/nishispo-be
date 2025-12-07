<?php 
namespace App\Filters;

use App\Enums\ArticleStatusEnum;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ArticleStatusFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if (!is_array($value)) {
            $value = explode(',', $value); 
        }

        $statuses = array_map(fn($status) => ArticleStatusEnum::tryFrom($status)?->value, $value);
        $statuses = array_filter($statuses);

        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }
    }
}