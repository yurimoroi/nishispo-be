<?php

namespace App\Modules\Event\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class EventCategory extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'id',
        'team_id',
        'name',
        'color',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
