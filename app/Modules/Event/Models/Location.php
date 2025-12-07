<?php

namespace App\Modules\Event\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class Location extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'team_id',
        'name',
        'location_category_id',
        'address',
        'description',
        'contact',
        'map_url',
        'google_map_flg',
        'latitude',
        'longitude',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function locationCategory()
    {
        return $this->belongsTo(LocationCategory::class, 'location_category_id');
    }

    public function eventsAsPrimaryLocation()
    {
        return $this->hasMany(Event::class, 'location_id');
    }

    public function eventsAsAggregationLocation()
    {
        return $this->hasMany(Event::class, 'aggregation_location_id');
    }
}
