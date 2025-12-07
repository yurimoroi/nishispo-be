<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $casts = [
        'published_at' => 'datetime',
        'publish_ended_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getPublishedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone(config('app.timezone'))->format('Y-m-d H:i:s') : null;
    }

    public function getPublishEndedAtAttribute($value)
    {
        return $value ?  Carbon::parse($value)->timezone(config('app.timezone'))->format('Y-m-d H:i:s') : null;
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone(config('app.timezone'))->format('Y-m-d H:i:s') : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone(config('app.timezone'))->format('Y-m-d H:i:s') : null;
    }
}
