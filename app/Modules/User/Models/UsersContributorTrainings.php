<?php

namespace App\Modules\User\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;

class UsersContributorTrainings extends Model
{
    use HasFactory, HasUlid, SoftDeletes;
    
    protected $fillable = [
        'id',
        'contributor_training_id',
        'user_id'
    ];

    public function contributorTraining()
    {
        return $this->belongsTo(User::class, 'users_contributor_trainings');
    }
}
