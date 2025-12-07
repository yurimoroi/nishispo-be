<?php

namespace App\Modules\Weather\Models;

use App\Modules\Company\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class Weather extends Model
{
    use HasFactory, HasUlid, SoftDeletes, BelongsToCompany;

    protected $table = 'weather_settings';

    protected $fillable = [
        'id',
        'company_id',
        'area_code',
        'sub_area_code'
    ];
}
