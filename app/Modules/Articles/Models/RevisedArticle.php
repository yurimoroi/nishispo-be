<?php

namespace App\Modules\Articles\Models;

use App\Modules\Company\Models\Organization;
use App\Modules\User\Models\User;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RevisedArticle extends Model implements HasMedia
{
    use InteractsWithMedia,HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'article_id',
        'user_id',
        'request_type',
        'title',
        'body',
        'organization_id',
        'published_at',
        'publish_ended_at',
        'comment',
    ];

    protected $appends = [
        'all_media_url',
    ];

    public function getAllMediaUrlAttribute()
    {
        $medias = $this->getMedia("*");
        $allMedia = [];

        if ($medias) {
            $allMedia = $medias->map(fn($media) => [
                'id' => $media->id,
                'original' => $media->getUrl(),
                'carousel' => $media->getUrl('carousel'),
                'thumbnail-medium' => $media->getUrl('thumbnail-medium'),
                'thumbnail-small' => $media->getUrl('thumbnail-small'),
            ])->toArray();
        }

        return $allMedia;
    }

    

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function revisedArticleCategories()
    {
        return $this->belongsToMany(ArticleCategory::class, 'rev_art_cat')->withTimestamps();
    }

    public function revisedArticleTags()
    {
        return $this->belongsToMany(ArticleTag::class, 'rev_art_tags')->withTimestamps();
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
    }
}
