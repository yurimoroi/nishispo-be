<?php

namespace App\Modules\Articles\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleViewingLogs extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'article_id',
        'user_id',
        'deleted_at',
    ];

    public function article() : BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }   
}
