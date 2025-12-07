<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupWarePrivilegesResource extends JsonResource
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
            'team' => new TeamResource($this->whenLoaded('team')), // Relationship with Team
            'privilage_started_at' => Carbon::parse($this->privilage_started_at)->format('Y/m/d H:i'),
            'privilage_ended_at' => Carbon::parse($this->privilage_ended_at)->format('Y/m/d H:i'),
            'nominal' => $this->nominal,
            'price' => $this->price,
            'payment_flg' => $this->payment_flg,
        ];
    }
}