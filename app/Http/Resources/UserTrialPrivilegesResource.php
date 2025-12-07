<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTrialPrivilegesResource extends JsonResource
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
            'team' => new TeamResource($this->whenLoaded('team')), // Assuming you have a TeamResource
            'user' => new UserResource($this->whenLoaded('user')), // Assuming you have a UserResource
            'trial_started_at' => Carbon::parse($this->trial_started_at)->format('Y/m/d H:i'),
            'trial_ended_at' => Carbon::parse($this->trial_ended_at)->format('Y/m/d H:i'),
            'trial_enable_flg' => $this->trial_enable_flg,
            'trial_duration' => $this->trial_duration, // Optional computed field for the trial duration
        ];
    }
}
