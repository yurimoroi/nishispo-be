<?php

namespace App\Modules\Company\Models;

use App\Enums\OrganizationUserStatus;
use App\Modules\Event\Models\Team;
use App\Modules\User\Models\User;
use App\Traits\BelongsToCompany;
use App\Traits\HasUlid;
use Database\Factories\OrganizationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Organization extends Model implements HasMedia
{
    use HasFactory, HasUlid, BelongsToCompany, SoftDeletes,InteractsWithMedia;

    const DEFAULT_PAGINATE = 5;

    protected static function newFactory()
    {
        return OrganizationFactory::new();
    }

    protected $fillable = [
        'name',
        'representative_name',
        'tel_number',
        'email',
        'activity_description',
        'birthyear_viewing_flg',
        'birthdate_viewing_flg',
        'postal_code_viewing_flg',
        'address_viewing_flg',
        'phone_number_viewing_flg',
        'mobile_phone_number_viewing_flg',
        'email_viewing_flg',
    ];

    protected $appends = [
        'logo',
    ];

    public function getLogoAttribute()
    {
        return  $this->getFirstMediaUrl(collectionName : 'organization-logo');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'organizations_users')->withTimestamps();
    }

    public function adminUsers()
    {
        return $this->belongsToMany(User::class, 'organizations_users')
        ->wherePivot('administrator_flg' , 1)
        ->withTimestamps();
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
