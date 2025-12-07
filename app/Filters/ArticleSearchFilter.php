<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ArticleSearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {

        $fieldToSearch = [
            'title',
            'body',
        ];

        $multiple = explode(" ", $value);

        $query->where(function ($q) use ($fieldToSearch, $multiple) {
            array_map(function ($field) use ($q, $multiple) {
                foreach ($multiple as $word) {
                    $q->orWhere($field, 'LIKE', "%{$word}%");
                }
            }, $fieldToSearch);
        });
    }
}
