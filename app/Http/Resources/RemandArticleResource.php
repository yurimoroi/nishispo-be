<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RemandArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'comment_to_title' => $this->comment_to_title,
            'comment_to_body' => $this->comment_to_body,
            'comment_to_image' => $this->comment_to_image,
            'comment' => $this->comment,
            'updated_at_diffForHumans' => Carbon::parse($this->updated_at)->diffForHumans(),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y/m/d H:i:s')
        ];
    }
}
