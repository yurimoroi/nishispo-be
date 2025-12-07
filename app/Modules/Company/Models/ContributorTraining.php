<?php

namespace App\Modules\Company\Models;

use App\Modules\User\Models\User;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContributorTraining extends Model
{
    use HasFactory, SoftDeletes, HasUlid;

    const LIMIT_PER_PAGE = 25;

    protected $fillable = [
        'type',
        'title',
        'url',
        'no',
        'overview',
    ];

    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class,'users_contributor_trainings','contributor_training_id','user_id')
        ->withTimestamps();
    }
}
