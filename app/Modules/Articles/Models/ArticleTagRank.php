<?php

namespace App\Modules\Articles\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleTagRank extends Model
{
    use HasFactory,HasUlid;

    protected $fillable = [
        'article_tag_id',
        'count',
    ];

    public function article() : BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
