<?php

namespace App\Modules\Articles\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleViewingRank extends Model
{
    use HasFactory,SoftDeletes,HasUlid;

    protected $fillable = [
        'article_id',
        'view_count',
    ];

    public function article() : BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
