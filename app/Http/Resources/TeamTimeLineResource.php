<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamTimeLineResource extends JsonResource
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
            'type' => $this->type,
            'item_id' => $this->item_id,
            'team' => new TeamResource($this->whenLoaded('team')), 
            'user' => new ArticleOwnerResource($this->whenLoaded('user')),
        ];
    }
}
