<?php

namespace App\Modules\Event\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Company\Models\Organization;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Event extends Model implements HasMedia
{
    use HasFactory, HasUlid, SoftDeletes, InteractsWithMedia;

    const LIMIT_PER_PAGE = 25;

    protected $fillable = [
        'id',
        'team_id',
        'user_id',
        'event_category_id',
        'name',
        'description',
        'started_at',
        'ended_at',
        'all_day_flg',
        'activity_location_type',
        'location_id',
        'location_name',
        'aggregation_location_flg',
        'aggregation_location_type',
        'aggregation_location_id',
        'aggregation_location_name',
        'team_group_id',
        'attendance_flg',
        'capacity',
        'reply_deadline',
        'not_other_answer_flg',
        'late_declaration_flg',
        'leave_early_declaration_flg',
        'show_participant_list_type',
        'show_participant_classification_type',
        'save_timeline_flg',
        'notification_setting',
        'repetition_flg',
        'repetition_event_hash',
        'repetition_started_at',
        'repetition_ended_type',
        'repetition_ended_at',
        'repetition_interval_type',
        'repetiton_week',
        'repetition_month_basis_type',
        'repetition_month_day',
        'repetition_week_of_month',
        'repetition_day_of_week',
    ];

    protected $appends = [
        'logo',
    ];

    public function getLogoAttribute()
    {
        return  $this->getFirstMediaUrl(collectionName : 'event-images');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventCategory()
    {
        return $this->belongsTo(EventCategory::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function aggregationLocation()
    {
        return $this->belongsTo(Location::class, 'aggregation_location_id');
    }

    public function teamGroups()
    {
        return $this->belongsToMany(TeamGroups::class, 'events_team_groups', 'event_id', 'team_group_id');
    }

    public function eventComments()
    {
        return $this->hasMany(EventComment::class);
    }

    public function eventReplies()
    {
        return $this->hasMany(EventReply::class);
    }

    public function eventReplyRequests()
    {
        return $this->hasMany(EventReplyRequest::class);
    }
}
