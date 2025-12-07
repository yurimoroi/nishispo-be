<?php

namespace App\Filters;

use App\Enums\ArticleStatusEnum;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class UserSearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $fieldToSearch = [
            'family_name',
            'given_name',
            'phonetic_family_name',
            'phonetic_given_name',
            'nickname',
            'postal_code',
            'province',
            'address_1',
            'address_2',
            'address_3',
            'email',
            'contributor_name',
            'favorite_gourmet',
            'favorite_sport',
            'login_id'
        ];

        $multiple = explode(" ", $value);

        $query->where(function ($q) use ($fieldToSearch, $multiple) {
            array_map(function ($field) use ($q, $multiple) {
                $q->orWhereIn($field, $multiple);
            }, $fieldToSearch);
        });
    }
}
