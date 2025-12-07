<?php

namespace App\Modules\Admin\Inquiry\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class InquiryType extends Model
{
    use HasFactory, HasUlid, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'id',
        'company_id', 
        'name',
    ];

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }
}
