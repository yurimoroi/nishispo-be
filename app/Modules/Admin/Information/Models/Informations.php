<?php

namespace App\Modules\Admin\Information\Models;

use App\Modules\Company\Models\Company;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Illuminate\Database\Eloquent\Model;

class Informations extends Model implements HasMedia
{
    use HasFactory, HasUlid, SoftDeletes, BelongsToCompany, InteractsWithMedia;

    const LIMIT_PER_PAGE = 25;

    protected $fillable = [
        'id',
        'company_id',
        'title',
        'body',
        'published_at',
        'finished_at',
    ];

    protected $appends = [
        'logo',
    ];

    public function getLogoAttribute()
    {
        return  $this->getFirstMediaUrl(collectionName : 'info-images');
    }
}
