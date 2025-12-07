<?php

namespace App\Modules\Articles\Models;

use App\Traits\BelongsToCompany;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends Model
{
    use HasFactory, HasUlid, SoftDeletes, BelongsToCompany;

    // //ARTICLE CATEGORY
    const ARTICLE_CATEGORY_PER_PAGE = 12;
    const EVENT_CATEGORY = 'イベント';
    
    protected $fillable = [
        'id',
        'company_id',
        'name',
        'color',
        'show_head_flg',
        'order',
        'special_flg',
        'short_name'
    ];
 

    public function articles() : BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'articles_article_categories')->withTimestamps();
    }
}
