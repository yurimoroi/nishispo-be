<?php

namespace App\Modules\Articles\Models;

use App\Traits\HasUlid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TopArticle extends Model implements HasMedia
{
    const LIMIT = 10;
    use HasFactory, HasUlid, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'id',
        'article_id',
        'order',
        'published_at',
        'publish_ended_at',
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function scopePublished($query)
    {
        $date = Carbon::now();

        return $query->where('published_at', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->where('publish_ended_at', '>=', $date)
                    ->orWhereNull('publish_ended_at');
            });
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
