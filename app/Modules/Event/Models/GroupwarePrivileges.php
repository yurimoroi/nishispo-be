<?php

namespace App\Modules\Event\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class GroupwarePrivileges extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'id',
        'team_id',
        'privilage_started_at',
        'privilage_ended_at',
        'nominal',
        'price',
        'payment_flg'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}