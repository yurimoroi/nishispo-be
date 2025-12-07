<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventStoreResource extends JsonResource
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
            'team_id' => $this->team_id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'event_category_id' => $this->event_category_id,
            'location_id' => $this->location_id,
            'team_group_id' => $this->team_group_id,
            'repetition_week' => $this->repetition_week ?? 'N/A',
            'aggregation_location_id' => $this->aggregation_location_id,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'activity_location_type' => $this->activity_location_type,
            'id' => $this->id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'logo' => $this->logo,
        ];
    }
}
