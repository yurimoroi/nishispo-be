<?php

namespace App\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class UserRoleFilter implements Filter
{
    private const ROLE_FLAGS = [
        'Secretariat' => ['field' => 'secretariat_flg', 'value' => 1],
        'Contributor' => ['field' => 'contributor_status', 'value' => 3],
        'Organization' => ['relationship' => 'organizationUser', 'field' => 'administrator_flg', 'value' => 1],
        'Event' => ['relationship' => 'teams', 'field' => 'leader_flg', 'value' => 1],
        'General' => [
            ['field' => 'secretariat_flg', 'value' => 0],
            ['field' => 'contributor_status', 'value' => 0]
        ]
    ];

    public function __invoke(Builder $query, $value, string $property): void
    {
        if (empty($value)) return;

        if (!is_array($value)) {
            $value = [$value];
        }

        $query->where(function ($q) use ($value) {
            foreach ($value as $role) {
                if (isset(self::ROLE_FLAGS[$role])) {
                    $filters = self::ROLE_FLAGS[$role];

                    if (isset($filters['relationship'])) {
                        $q->orWhereHas($filters['relationship'], function ($relationQuery) use ($filters) {
                            $relationQuery->where($filters['field'], $filters['value']);
                        });
                    } else {
                        if ($role === 'General') {
                            foreach ($filters as $filter) {
                                $q->where($filter['field'], $filter['value']);
                            }
                        } else {
                            $q->orWhere($filters['field'], $filters['value']);
                        }
                    }
                }
            }
        });
    }
}
