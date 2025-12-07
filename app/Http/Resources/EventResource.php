<?php

namespace App\Http\Resources;

use App\Enums\EventActivityLocationTypeEnum;
use App\Enums\EventAggregationLocationTypeEnum;
use App\Enums\EventLateDeclarationEnum;
use App\Enums\EventNoOtherAnswerEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{

    
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'started_at' => Carbon::parse($this->started_at)->format('Y/m/d H:i'),
            'ended_at' => Carbon::parse($this->ended_at)->format('Y/m/d H:i'),
            'all_day_flg' => $this->all_day_flg,
            'activity_location_type' => $this->activity_location_type,
            'activity_location' => [
                'status' => $this->activity_location_type,
                'label' => EventActivityLocationTypeEnum::from($this->activity_location_type)->label
            ],
            'location_name' => $this->location_name,
            'aggregation_location_flg' => $this->aggregation_location_flg,
            'aggregation_location_type' => $this->aggregation_location_type,
            'aggregation_location' => [
                'status' => $this->aggregation_location_type,
                'label' => EventAggregationLocationTypeEnum::from((int)$this->aggregation_location_type)->label
            ],
            'aggregation_location_name' => $this->aggregation_location_name,
            'team_group_id' => $this->team_group_id,
            'attendance_flg' => $this->attendance_flg,
            'capacity' => $this->capacity,
            'reply_deadline' => Carbon::parse($this->reply_deadline)->format('Y/m/d H:i'),
            'not_other_answer_flg' => $this->not_other_answer_flg,
            'no_answer' => [
                'status' => $this->not_other_answer_flg,
                'label' => EventNoOtherAnswerEnum::from($this->not_other_answer_flg)->label
            ],
            'late_declaration_flg' => $this->late_declaration_flg,
            'late_declaration' => [
                'status' => $this->late_declaration_flg,
                'label' => EventLateDeclarationEnum::from($this->late_declaration_flg)->label
            ],
            'leave_early_declaration_flg' => $this->leave_early_declaration_flg,
            'leave_early' => [
                'status' => $this->late_declaration_flg,
                'label' => EventLateDeclarationEnum::from($this->late_declaration_flg)->label
            ],
            'show_participant_list_type' => $this->show_participant_list_type,
            'show_participant_list' => [
                'status' => $this->show_participant_list_type,
                'label' => EventLateDeclarationEnum::from($this->show_participant_list_type)->label
            ],
            'show_participant_classification_type' => $this->show_participant_classification_type,
            'show_participant_classification' => [
                'status' => $this->show_participant_classification_type,
                'label' => EventLateDeclarationEnum::from($this->show_participant_classification_type)->label
            ],
            'save_timeline_flg' => $this->save_timeline_flg,
            'notification_setting' => $this->notification_setting,
            'repetition_flg' => $this->repetition_flg,
            'repetition_event_hash' => $this->repetition_event_hash,
            'repetition_started_at' => Carbon::parse($this->repetition_started_at)->format('Y/m/d H:i'),
            'repetition_ended_type' => $this->repetition_ended_type,
            'repetition_ended_at' => Carbon::parse($this->repetition_ended_at)->format('Y/m/d H:i'),
            'repetition_interval_type' => $this->repetition_interval_type,
            'repetiton_week' => $this->repetiton_week,
            'repetition_month_basis_type' => $this->repetition_month_basis_type,
            'repetition_month_day' => $this->repetition_month_day,
            'repetition_week_of_month' => $this->repetition_week_of_month,
            'repetition_day_of_week' => $this->repetition_day_of_week,
            'team' => new TeamResource($this->whenLoaded('team'), $this->isEdit), 
            'user' => new UserResource($this->whenLoaded('user')), 
            'event_category' => new EventCategoryResource($this->whenLoaded('eventCategory')), 
            'location' => new LocationResource($this->whenLoaded('location')), 
            'aggregation_location' => new LocationResource($this->whenLoaded('aggregationLocation')), 
            'event_comments' => EventCommentResource::collection($this->whenLoaded('eventComments')), 
            'event_replies' => EventReplyResource::collection($this->whenLoaded('eventReplies')), 
            'event_reply_requests' => EventReplyRequestResource::collection($this->whenLoaded('eventReplyRequests')),
            'event_images' => $this->logo,
        ];
    }
}