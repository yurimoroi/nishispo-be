<?php

namespace App\Traits;

use App\Modules\Company\Models\Company;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany()
    {
        static::addGlobalScope('company_id', function (Builder $builder) {
            // $company_id = app()->bound('currentCompanyId') ? app('currentCompanyId') : null;
            $company_id = 1;
            if ($company_id) {
                $builder->where('company_id', $company_id);
            }
        });

        static::creating(function ($model) {
            $company = Company::first();
            $model->company_id =  $company->id;
            
            // if (!$model->company_id && app()->bound('currentCompanyId')) {
            //     $model->company_id = app('currentCompanyId');
            // }
        });
    }

    public function scopeForCurrentCompany(Builder $query)
    {
        $company_id =  app('currentCompanyId');

        return $query->where('company_id', $company_id);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
