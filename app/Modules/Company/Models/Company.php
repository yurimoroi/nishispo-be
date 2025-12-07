<?php

namespace App\Modules\Company\Models;

use App\Modules\Admin\Information\Models\Informations;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\ArticleCategory;
use App\Modules\Articles\Models\ArticleTag;
use App\Modules\User\Models\User;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'tel_number',
        'leader_name',
        'email',
        'terms',
        'terms_updated_at',
        'about_service',
        'about_service_updated_at',
        'about_company',
        'about_company_updated_at',
        'privacy_policy',
        'privacy_policy_updated_at',
        'about_report',
        'about_report_updated_at',
        'ad',
        'ad_updated_at',
        'reporter_editor',
        'reporter_editor_updated_at',
        'about_publish_content',
        'about_publish_content_updated_at',
        'post_limit',
        'organization_member_post_limit',
        'organization_post_limit',
        'deleted_at',
    ];

    protected $casts = [
        'post_limit' => 'integer',
        'organization_member_post_limit' => 'integer',
        'organization_post_limit' => 'integer'
    ];

    protected static function booted()
    {
        static::updating(function ($company) {

            if ($company->isDirty('terms')) {
                $company->terms_updated_at = now();
            }

            if ($company->isDirty('about_service')) {
                $company->about_service_updated_at = now();
            }

            if ($company->isDirty('about_company')) {
                $company->about_company_updated_at = now();
            }

            if ($company->isDirty('privacy_policy')) {
                $company->privacy_policy_updated_at = now();
            }

            if ($company->isDirty('about_report')) {
                $company->about_report_updated_at = now();
            }

            if ($company->isDirty('ad')) {
                $company->ad_updated_at = now();
            }

            if ($company->isDirty('reporter_editor')) {
                $company->reporter_editor_updated_at = now();
            }

            if ($company->isDirty('about_publish_content')) {
                $company->about_publish_content_updated_at = now();
            }
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(ArticleCategory::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(ArticleTag::class);
    }

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }

    public function organizationsUsers(): HasMany
    {
        return $this->hasMany(Organization::class)->with('users');
    }

    public function alignmentMedia(): HasMany
    {
        return $this->hasMany(AlignmentMedia::class);
    }

    public function informations()
    {
        return $this->hasMany(Informations::class);
    }
}
