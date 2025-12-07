<?php

namespace App\Modules\Event\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;


class LocationCategory extends Model
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
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
