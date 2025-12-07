<?php 
namespace App\Filters;

use App\Enums\ArticleStatusEnum;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ArticleRelationFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('articles' , function(Builder $query) use ($value){
            $query->where('status' , ArticleStatusEnum::from($value)->value);
        });
    }
}