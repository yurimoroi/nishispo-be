<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleRankingResource extends JsonResource
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
            "article_id" => $this->article_id,
            "view_count" => $this->view_count,
            // "updated_at" => Carbon::parse($this->updated_at)->format('Y.m.d'),
            "article" => new ArticleResource($this->whenLoaded('article')),
        ];
    }
}
