<?php

namespace App\Modules\Articles\Models;

use App\Traits\BelongsToCompany;
use App\Traits\HasUlid;
use Database\Factories\ArticleTagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleTag extends Model
{
    use HasFactory, SoftDeletes,HasUlid,BelongsToCompany;

    const LIMIT_ITEMS = 20;

    protected $fillable = [
        'id',
        'company_id',
        'name',
    ];

    protected static function newFactory()
    {
        return ArticleTagFactory::new();
    }

    public function articles() : BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'articles_article_tags')->withTimestamps();
    }
}
