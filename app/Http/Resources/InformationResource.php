<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'title' => $this->title,
            'body' => $this->body,
            'published_at' => Carbon::parse($this->published_at)->format(config('app.default_date_time_format')),
            'finished_at' => $this->finished_at ? Carbon::parse($this->finished_at)->format(config('app.default_date_time_format')) : null,
            'is_new' => Carbon::parse($this->published_at)->isBetween(now()->subDays(7) , now()),
            'info_images' => $this->logo,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'created_at' => Carbon::parse($this->created_at)->format(config('app.default_date_time_format')),
            'created_at_diffForHumans' => Carbon::parse($this->created_at)->diffForHumans()
            
        ];
    }
}
