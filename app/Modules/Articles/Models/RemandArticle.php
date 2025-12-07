<?php

namespace App\Modules\Articles\Models;

use App\Modules\User\Models\User;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RemandArticle extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'article_id',
        'user_id',
        'comment_to_title',
        'comment_to_body',
        'comment_to_image',
        'comment',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
