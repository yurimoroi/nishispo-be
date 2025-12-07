<?php

namespace App\Modules\Articles\Models;

use App\Enums\ArticleEnum;
use App\Enums\ArticleStatusEnum;
use App\Models\BaseModel;
use App\Modules\Company\Models\Company;
use App\Modules\Company\Models\Organization;
use App\Modules\User\Models\User;
use App\Traits\BelongsToCompany;
use App\Traits\HasUlid;
use Carbon\Carbon;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Modules\Company\Models\AlignmentMedia;

class Article extends BaseModel implements HasMedia
{
    use HasFactory, HasUlid, SoftDeletes, BelongsToCompany, InteractsWithMedia;

    // ARTICLE DISPLAY LIMIT
    const LIMIT_PER_PAGE = 25;
    const TOP_PAGE_ARTICLE_LIMIT = 9;
    const CATEGORY_ARTICLES_LIMIT = 4;
    const TOP_PAGE_TOP_ARTICLE_LIMIT = 6;
    const RELATED_ARTICLES_LIMIT = 3;
    const RANK_ARTICLE_LIMIT = 3;

    protected $fillable = [
        'id',
        'company_id',
        'user_id',
        'title',
        'body',
        'organization_id',
        'pr_flg',
        'status',
        'published_at',
        'publish_ended_at',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'pr_flg' => 'boolean',
    ];

    protected $appends = [
        'all_media_url',
        'body_characters_count'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->user_id = Auth::user()->id;
                $model->status = ArticleStatusEnum::draft()->value;
            }
        });
    }

    public function getBodyCharactersCountAttribute()
    {
        return mb_strlen($this->body, 'UTF-8');
    }

    protected static function newFactory()
    {
        return ArticleFactory::new();
    }

    public function getAllMediaUrlAttribute()
    {
        //return cache()->remember("article_{$this->id}_media_urls", now()->addMinutes(60), function () {
            $medias = $this->getMedia('*');
            $allMedia = [];

            if ($medias) {
                $allMedia = $medias->map(fn($media) => [
                    'id' => $media->id,
                    'original' => $media->mime_type !== 'image/heic' ?  $media->getUrl() : $media->getUrl('converted'),
                    'carousel' => $media->getUrl('carousel'),
                    'thumbnail-medium' => $media->getUrl('thumbnail-medium'),
                    'thumbnail-small' => $media->getUrl('thumbnail-small'),
                ])->toArray();
            }

            return $allMedia;
        //});
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('carousel')
            ->width(860)
            ->height(424);


        $this->addMediaConversion('thumbnail-medium')
            ->width(300)
            ->height(300);


        $this->addMediaConversion('thumbnail-small')
            ->width(100)
            ->height(100);

        if ($media && strtolower($media->mime_type) === 'image/heic') {
            $this->addMediaConversion('converted')
                ->format('jpg')
                ->quality(90);
        }
    }

    public function scopePublished($query)
    {
        $date = Carbon::now();

        return $query
            ->where(function ($q) {
                $q->where('status', ArticleStatusEnum::published()->value)
                    ->orWhere('status', ArticleStatusEnum::requestEdit()->value)
                    ->orWhere('status', ArticleStatusEnum::requestDelete()->value);
            })
            ->where('published_at', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->where('publish_ended_at', '>=', $date)
                    ->orWhereNull('publish_ended_at');
            })
            ->whereHas('user', function ($q) {
                $q->whereNull('deleted_at');
            });
    }


    public function scopeCompanyPostLimitCount($query)
    {
        return $query->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()]);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ArticleCategory::class, 'articles_article_categories')->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ArticleTag::class, 'articles_article_tags')
            ->select(['article_tags.id', 'article_tags.name'])
            ->withTimestamps();
    }

    public function alignment_medias(): BelongsToMany
    {
        return $this->belongsToMany(AlignmentMedia::class, 'article_alignment_media')->withTimestamps();
    }

    public function remand(): HasOne
    {
        return $this->hasOne(RemandArticle::class);
    }

    public function revised(): HasOne
    {
        return $this->hasOne(RevisedArticle::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class)->select(['id', 'name', 'representative_name', 'activity_description']);
    }

    public function relatedArticleByTags()
    {
        return $this->tags()->with(['articles' => function ($q) {
            $q->published()
                ->with('user')
                ->where('articles_article_tags.id', '!=', $this->id)
                ->orderBy('updated_at', 'desc')
                ->limit(self::RELATED_ARTICLES_LIMIT);
        }]);
    }

    public function relatedArticleByCategories()
    {
        return $this->categories()->with(['articles' => function ($q) {
            $q->published()
                ->with('user')
                ->where('articles_article_categories.id', '!=', $this->id)
                ->orderBy('updated_at', 'desc')
                ->limit(self::RELATED_ARTICLES_LIMIT);
        }]);
    }

    public function isTopArticle()
    {
        return $this->hasOne(TopArticle::class, 'article_id')->exists();
    }

    public function topArticle(): HasOne
    {
        return $this->hasOne(TopArticle::class, 'article_id');
    }
}
