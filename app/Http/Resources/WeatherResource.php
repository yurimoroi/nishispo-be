<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
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
            'company_id' => $this->company_id,
            'area_code' => $this->area_code,
            'sub_area_code' => $this->sub_area_code,
            'company' => new CompanyResource($this->whenLoaded('company')),
        ];
    }
}
