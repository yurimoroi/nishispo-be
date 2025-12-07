<?php

namespace App\Http\Resources;

use App\Enums\RevisedArticleEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleRevisionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $type = RevisedArticleEnum::from($this->request_type);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'request' => [
                'type' =>  $type->value,
                'label' => $type->label
            ],
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            'published_at' => Carbon::parse($this->published_at)->format('Y/m/d H:i'),
            'publish_at_diffForHumans' => Carbon::parse($this->published_at)->diffForHumans(),
            'publish_ended_at' => Carbon::parse($this->publish_ended_at)->format('Y/m/d H:i'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y.m.d'),
            'updated_at_diffForHumans' => Carbon::parse($this->updated_at)->diffForHumans(),
            'comment' => $this->comment,
            'all_media_url' => $this->all_media_url,
            'tags' => TagResource::collection($this->whenLoaded('revisedArticleTags')),
            'categories' => TagResource::collection($this->whenLoaded('revisedArticleCategories'))
        ];
    }
}
