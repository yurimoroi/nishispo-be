<?php

namespace App\Modules\Company\Models;

use App\Enums\OrganizationUserStatus;
use App\Modules\User\Models\User;
use App\Notifications\OrganizationMembershipApproved;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationUser extends Model
{
    use HasFactory, HasUlid;

    protected $table = 'organizations_users';

    protected $fillable = [
        'id',
        'organization_id',
        'user_id',
        'status',
        'administrator_flg',
    ];

    const APPLYING_MEMBERSHIP = 0;
    const AFFILIATION = 1;
    const APPLYING_WITHDRAWAL = 2;
    const WITHDRAWAL = 3;
    const APPROVE = 4;

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization() : BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function userApprovedNotify()
    {
        $this->update(['status' => OrganizationUserStatus::afilliation()->value]);
        $this->user->notify((new OrganizationMembershipApproved($this->organization)));
    }
}
