<?php

namespace App\Modules\Event\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;


class TeamGroups extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'id',
        'team_id',
        'name',
        'description',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
