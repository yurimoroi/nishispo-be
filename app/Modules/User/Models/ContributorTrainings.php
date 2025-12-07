<?php

namespace App\Modules\User\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use App\Modules\User\Models\UsersContributorTrainings;

class ContributorTrainings extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'id',
        'type',
        'title',
        'url',
        'no',
        'overview',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function usersContributorTrainings()
    {
        return $this->hasOne(UsersContributorTrainings::class,
            'contributor_training_id',
            'id'
        );
    }
}
