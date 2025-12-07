<?php 
namespace App\Filters;

use App\Enums\UserContributorStatus;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class UserContributorFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where('contributor_status' , UserContributorStatus::from($value));
    }
}