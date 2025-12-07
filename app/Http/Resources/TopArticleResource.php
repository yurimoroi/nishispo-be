<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TopArticleResource extends JsonResource
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
            'order' => $this->order,
            'published_at' =>  Carbon::parse($this->published_at)->format('Y/m/d H:i'),
            'publish_ended_at' => $this->publish_ended_at ? Carbon::parse($this->publish_ended_at)->format('Y/m/d H:i') : null,
            'article' => $this->whenLoaded('article', function () {
                return new ArticleResource($this->article);
            }),
        ];
    }
}
