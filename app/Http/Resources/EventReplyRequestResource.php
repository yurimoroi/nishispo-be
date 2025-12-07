<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventReplyRequestResource extends JsonResource
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
            'event' => new EventResource($this->whenLoaded('event')), // Relationship with Event
            'user' => new UserResource($this->whenLoaded('user')),   // Relationship with User
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),  // Formatting created_at timestamp with Carbon
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString(),  // Formatting updated_at timestamp with Carbon
        ];
    }
}
