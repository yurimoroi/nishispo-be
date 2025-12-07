<?php

namespace App\Modules\Company\Models;

use App\Modules\Articles\Models\Article;
use App\Traits\BelongsToCompany;
use App\Traits\HasUlid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AlignmentMedia extends Model implements HasMedia
{
    use HasFactory,BelongsToCompany,HasUlid,SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'company_id',
        'name',
        'url',
        'order',
        'display_top_flg',
        'display_article_list_flg',
        'display_flg',
        'note',
        'started_at',
        'ended_at',
        'deleted_at',
    ];

    protected $appends = [
        'banner',
    ];

    public function getBannerAttribute()
    {
        return  $this->getFirstMediaUrl(collectionName : 'alignment-media-banner');
    }

    public function scopePublished($query)
    {
        $date = Carbon::now();

        return $query->where('started_at', '<=', $date)
            ->where('ended_at', '>=', $date);
    }

    public function articles() : BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_alignment_media')->withTimestamps();
    }
}
