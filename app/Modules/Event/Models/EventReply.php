<?php

namespace App\Modules\Event\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use App\Modules\User\Models\User;

use Illuminate\Database\Eloquent\Model;

class EventReply extends Model
{
    use HasFactory, HasUlid, SoftDeletes;

    protected $fillable = [
        'id',
        'event_id',
        'user_id',
        'answer',
        'memo',
        'late_declaration_flg',
        'arrival_time',
        'leave_early_declaration_flg',
        'leave_early_time',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
