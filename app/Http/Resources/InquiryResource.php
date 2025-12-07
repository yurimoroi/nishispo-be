<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InquiryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "body" => $this->body,
            "email" => $this->email,
            "reply" => $this->reply,
            "reply_flg" => !empty($this->reply) ??  false,
            "inquiry_type" => $this->whenLoaded('inquiryType' , function() {
                return [
                    "id" => $this->inquiryType->id,
                    'type' => $this->inquiryType->name
                ];
            }),
            "created_at" => Carbon::parse($this->created_at)->format(config('app.default_date_time_format')),
            "created_at_diffForHumans" => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
