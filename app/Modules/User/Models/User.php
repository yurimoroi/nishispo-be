<?php

namespace App\Modules\User\Models;

use App\Enums\ArticleStatusEnum;
use App\Enums\OrganizationUserStatus;
use App\Enums\UserContributorStatus;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\RemandArticle;
use App\Modules\Articles\Models\RevisedArticle;
use App\Modules\Company\Models\ContributorTraining;
use App\Modules\Company\Models\Organization;
use App\Modules\Company\Models\OrganizationUser;
use App\Modules\Event\Models\Event;
use App\Modules\Event\Models\EventReply;
use App\Modules\Event\Models\Team;
use App\Modules\Event\Models\UserTrialPrivileges;
use App\Notifications\ChangePasswordNotification;
use App\Notifications\ContributorApplicationApproved;
use App\Notifications\NewUserNotification;
use App\Notifications\PasswordResetNotification;
use App\Notifications\RequestLoginIdNotification;
use App\Notifications\SendEmailVerificationNotif;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\BelongsToCompany;
use App\Traits\HasUlid;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, HasUlid, BelongsToCompany, InteractsWithMedia;

    const CONTRIBUTOR_NOT_APPLIED = 0;
    const CONTRIBUTOR_TRAINING = 1;
    const CONTRIBUTOR_TRAINING_COMPLETE = 2;
    const CONTRIBUTOR_APPROVE = 3;

    const LIMIT_PER_PAGE = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'id',
        'company_id',
        'family_name',
        'given_name',
        'phonetic_family_name',
        'phonetic_given_name',
        'nickname',
        'birth_date',
        'gender_type',
        'postal_code',
        'province',
        'address_1',
        'address_2',
        'address_3',
        'phone_number',
        'mobile_phone_number',
        'login_id',
        'email_verified_at',
        'password_reset_token',
        'contributor_name',
        'rakuten_id',
        'favorite_sport',
        'favorite_gourmet',
        'secretariat_flg',
        'contributor_status',
        'advertiser_flg',
        'advertiser_name',
        'line_id',
        'x_id',
        'facebook_id',
        'instagram_id',
    ];

    protected $with = [
        'affiliate',
        'company'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = [
        'avatar',
    ];

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function sendPasswordResetNotification($token)
    {
        $url = config('app.frontend_url') . '/password/reset?email=' . $this->email . '&token=' . $token;

        $seconds = now()->addSeconds(5);

        $this->notify((new PasswordResetNotification($url))->delay($seconds));
    }

    public function sendChangePasswordNotification($password)
    {
        $this->notify((new ChangePasswordNotification($password))->delay(now()->addSeconds(5)));
    }

    public function sendRequestLoginId()
    {
        $delay = now()->addSeconds(5);
        $this->notify((new RequestLoginIdNotification($this->login_id))->delay($delay));
    }

    public function contributorApplicationApproved()
    {
        $this->notify((new ContributorApplicationApproved())->delay(now()->addSeconds(5)));
    }

    public function getAvatarAttribute()
    {
        return  $this->getFirstMediaUrl(collectionName: 'avatar');
    }

    // public function registerMediaConversions(?Media $media = null): void
    // {
    //     $this->addMediaConversion('thumb')
    //         ->width(50)
    //         ->height(50)
    //         ->sharpen(10);
    // }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    public function isSecretariat(): bool
    {
        return $this->secretariat_flg;
    }

    public function isAdvertiser(): bool
    {
        return $this->advertiser_flg;
    }

    public function isContributor(): bool
    {
        return $this->contributor_status === UserContributorStatus::approved()->value;
    }

    public function newUserNotify()
    {
        $this->notify(new NewUserNotification());
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new SendEmailVerificationNotif());
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function remandArticles(): HasMany
    {
        return $this->hasMany(RemandArticle::class);
    }

    public function revisedArticles(): HasMany
    {
        return $this->hasMany(RevisedArticle::class);
    }

    public function organizationUser(): HasMany
    {
        return $this->hasMany(OrganizationUser::class);
    }

    public function trainings(): BelongsToMany
    {
        return $this->BelongsToMany(ContributorTraining::class, 'users_contributor_trainings');
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organizations_users')
            ->withPivot(['status', 'administrator_flg'])
            ->withTimestamps();
    }

    public function affiliate(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organizations_users')
            ->withPivot(['status', 'administrator_flg','id']);
    }

    public function isOrganizationAdmin(Organization $organization)
    {
        $this->organization()->wherePivot('organization_id', $organization->id)
            ->wherePivot('administrator_flg', true)
            ->exists();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function eventReplies()
    {
        return $this->hasMany(EventReply::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'teams_users', 'user_id', 'team_id')
            ->withPivot([
                'leader_flg',
                'status',
                'member_type',
                'current_team_flg',
                'purchase_status'
            ])
            ->withTimestamps();
    }

    public function scopeWithPublishedArticlesInRange(Builder $query, $startDate, $endDate)
    {
        $articleFilter = function ($query) use ($startDate, $endDate) {
            $query->whereBetween('published_at', [$startDate, $endDate])
                 ->whereRaw("DATE_ADD(published_at, INTERVAL 7 DAY) <= NOW()")
                ->whereIn('status', [
                    ArticleStatusEnum::published()->value,
                    ArticleStatusEnum::requestEdit()->value,
                    ArticleStatusEnum::requestDelete()->value,
                ]);
        };

        return $query->whereHas('articles', $articleFilter)
            ->with(['articles' => $articleFilter])
            ->withCount(['articles' => $articleFilter]);
    }
}
