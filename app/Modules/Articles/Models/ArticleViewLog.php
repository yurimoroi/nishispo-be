<?php

namespace App\Modules\Articles\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleViewLog extends Model
{
    use HasFactory,HasUlid;

    protected $fillable = [
        'article_id',
        'user_id',
        'ip_address',
        'user_agent',
    ];

    protected $table = 'article_viewing_logs';
}
