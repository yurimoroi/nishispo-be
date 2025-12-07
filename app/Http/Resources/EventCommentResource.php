<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventCommentResource extends JsonResource
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
            'comment' => $this->comment,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),  // Using Carbon to format the date
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString(),  // Using Carbon to format the date
        ];
    }
}
