<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleCategoryResource extends JsonResource
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
            'name' => $this->name,
            'short_name' => $this->short_name,
            'color' => $this->color,
            'show_head_flg' => $this->show_head_flg,
            'order' => $this->order,
            'special_flg' => $this->special_flg,
            'articles' => $this->whenLoaded('articles', function () {
                return ArticleResource::collection($this->articles);
            }),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y/m/d H:i')
        ];
    }
}
