<?php

namespace App\Modules\Event\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use App\Modules\User\Models\User;

class UserTrialPrivileges extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $table = 'user_trial_privileges';

    protected $fillable = [
        'team_id',
        'user_id',
        'trial_started_at',
        'trial_ended_at',
        'trial_enable_flg'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
