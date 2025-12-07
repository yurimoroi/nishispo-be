<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlignMediaResource extends JsonResource
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
            'url' => $this->url,
            'banner' => $this->banner,
            $this->mergeWhen(true , [
                'order' => $this->order,
                'display_top_flg' => $this->display_top_flg,
                'display_article_list_flg' => $this->display_article_list_flg,
                'display_flg' => $this->display_flg,
                'started_at' => Carbon::parse($this->started_at)->format('Y-m-d H:i:s'),
                'started_at_diffForHumans' => Carbon::parse($this->started_at)->diffForHumans(),
                'ended_at' => Carbon::parse($this->ended_at)->format('Y-m-d H:i:s'),
                'ended_at_diffForHumans' => Carbon::parse($this->ended_at)->diffForHumans(),
                'articles' => ArticleResource::collection($this->whenLoaded('articles'))
            ])
        ];
    }
}
