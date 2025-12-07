<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContributorTrainingsResource extends JsonResource
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
            'type' => $this->type,
            'title' => $this->title,
            'url' => $this->url,
            'no' => $this->no,
            'overview' => $this->overview,
            'users_contributor_trainings' => $this->whenLoaded('usersContributorTrainings' , function() {
                return $this->usersContributorTrainings;
            })
        ];

    }
}
