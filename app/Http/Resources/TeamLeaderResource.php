<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamLeaderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->id,
            'family_name' => $this->family_name,
            'given_name' => $this->given_name,
            'phonetic_family_name' => $this->phonetic_family_name,
            'phonetic_given_name' => $this->phonetic_given_name,
            'nickname' => $this->nickname
        ];
    }
}
