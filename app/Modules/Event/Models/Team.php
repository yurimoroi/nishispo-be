<?php

namespace App\Modules\Event\Models;

use App\Modules\Company\Models\Organization;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'id',
        'organization_id',
        'name',
        'activity_description',
        'member_information',
        'group_fee',
        'collect_type',
        'collect_span',
        'closing_date',
        'first_estimated_number',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function teamInviteTokens()
    {
        return $this->hasMany(TeamInviteToken::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function trialPrivileges()
    {
        return $this->hasMany(UserTrialPrivileges::class);
    }

    public function teamGroups()
    {
        return $this->hasMany(TeamGroups::class);
    }

    public function teamTimeLines()
    {
        return $this->belongsToMany(User::class, 'team_timelines');
    }

    public function eventCategories()
    {
        return $this->hasMany(EventCategory::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function groupwarePrivileges()
    {
        return $this->hasMany(GroupwarePrivileges::class);
    }

    public function teamUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class , 'teams_users')
        ->withPivot(['leader_flg'])
        ->withTimestamps();
    }

    public function leaders(): BelongsToMany
    {
        return $this->belongsToMany(User::class , 'teams_users')
        ->wherePivot('leader_flg' , 1)
        ->withTimestamps();
    }
}