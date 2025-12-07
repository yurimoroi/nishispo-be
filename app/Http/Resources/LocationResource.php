<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'team' => new TeamResource($this->whenLoaded('team')), // Assuming you have a TeamResource
            'location_category' => new LocationCategoryResource($this->whenLoaded('locationCategory')), // Assuming you have a LocationCategoryResource
            'name' => $this->name,
            'address' => $this->address,
            'description' => $this->description,
            'contact' => $this->contact,
            'map_url' => $this->map_url,
            'google_map_flg' => $this->google_map_flg,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'events_as_primary_location' => EventResource::collection($this->whenLoaded('eventsAsPrimaryLocation')), // Assuming you have an EventResource
            'events_as_aggregation_location' => EventResource::collection($this->whenLoaded('eventsAsAggregationLocation')), // Assuming you have an EventResource
        ];
    }
}
