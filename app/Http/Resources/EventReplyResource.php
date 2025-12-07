<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventReplyResource extends JsonResource
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
            'event' => new EventResource($this->whenLoaded('event')), // Assuming you have an EventResource
            'user' => new UserResource($this->whenLoaded('user')), // Assuming you have a UserResource
            'answer' => $this->answer,
            'memo' => $this->memo,
            'late_declaration_flg' => $this->late_declaration_flg,
            'arrival_time' => $this->arrival_time ? Carbon::parse($this->arrival_time)->toDateTimeString() : null,
            'leave_early_declaration_flg' => $this->leave_early_declaration_flg,
            'leave_early_time' => $this->leave_early_time ? Carbon::parse($this->leave_early_time)->toDateTimeString() : null,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString(),
        ];
    }
}
