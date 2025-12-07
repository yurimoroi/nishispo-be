<?php

namespace App\Http\Resources;

use App\Enums\ArticleStatusEnum;
use App\Enums\RevisedArticleEnum;
use App\Modules\Articles\Models\TopArticle;
use App\Modules\Company\Models\AlignmentMedia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status  = ArticleStatusEnum::from($this->status);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'body_characters_count' => $this->body_characters_count,
            'pr_flg' => $this->pr_flg,
            'status' => [
                'value' => $status->value,
                'label' => $status->label
            ],
            'published_at' => Carbon::parse($this->published_at)->format('Y/m/d H:i'),
            'publish_at_diffForHumans' => Carbon::parse($this->published_at)->diffForHumans(),
            'publish_ended_at' => $this->publish_ended_at ? Carbon::parse($this->publish_ended_at)->format('Y/m/d H:i') : null,
            'updated_at' => Carbon::parse($this->updated_at)->format('Y.m.d H:i'),
            'created_at' => Carbon::parse($this->created_at)->format('Y.m.d H:i'),
            'updated_at_diffForHumans' => Carbon::parse($this->updated_at)->diffForHumans(),
            'all_media_url' => $this->all_media_url,
            'user' => new ArticleOwnerResource($this->whenLoaded('user')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'categories' => ArticleCategoryResource::collection($this->whenLoaded('categories')),
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            'top_article' => new TopArticleResource($this->whenLoaded('topArticle')),
            "related_article_by_tags" => TagResource::collection($this->whenLoaded('relatedArticleByTags')),
            "related_article_by_categories" => ArticleCategoryResource::collection($this->whenLoaded('relatedArticleByCategories')),
            "remand_comments" => new RemandArticleResource($this->whenLoaded('remand')),
            "revision" => new ArticleRevisionResource($this->whenLoaded('revised')),
            "un_submitted_edits" => $this->whenLoaded('revised', function () {
                return $this->revised?->request_type === RevisedArticleEnum::temporarySave()->value ?? false;
            }),
            "alignment_medias" => AlignMediaResource::collection($this->whenLoaded('alignment_medias')),
            "btns" => [
                $this->mergeWhen($request->user(), [
                    "preview" => $request->user() && $request->user()->isSecretariat() ?
                        $status->value !== ArticleStatusEnum::published()->value :
                        $status->value === ArticleStatusEnum::published()->value ||
                        $status->value === ArticleStatusEnum::applyingPublish()->value ||
                        $status->value === ArticleStatusEnum::draft()->value,

                    "submit" => (
                        $status->value === ArticleStatusEnum::draft()->value  ||
                        $status->value === ArticleStatusEnum::remand()->value ||
                        $status->value === ArticleStatusEnum::applyingRemand()->value
                    ),

                    "save" => true,
                    "edit" => (
                        $status->value === ArticleStatusEnum::draft()->value  ||
                        $status->value === ArticleStatusEnum::remand()->value ||
                        $status->value === ArticleStatusEnum::applyingRemand()->value ||
                        ($request->user() && $request->user()->isSecretariat() ? $status->value === ArticleStatusEnum::published()->value : false)
                    ),
                    // - Displayed for articles with the status: In creation, In application, Rejected, In application (Rejected for correction).
                    // - modify remove the delete if the status is return
                    "delete" => (
                        $status->value === ArticleStatusEnum::applyingPublish()->value ||
                        $status->value === ArticleStatusEnum::draft()->value ||
                        ($request->user() && $request->user()->isSecretariat() ? $status->value === ArticleStatusEnum::published()->value : false)
                    ),


                    // - For articles with a published status, this will be displayed if there is post-publishing edit information.
                    "editRequest" => $status->value === ArticleStatusEnum::published()->value,

                    // - Displayed on posts with a published status.
                    "deleteRequest" => $status->value === ArticleStatusEnum::published()->value,

                    // - Displayed for articles with a status of being requested for editing.
                    // - Displayed for articles with a status of pending deletion.
                    "withdrawDeleteEditRequest" => (
                        $status->value === ArticleStatusEnum::requestDelete()->value ||
                        $status->value === ArticleStatusEnum::requestEdit()->value
                    ),
                ]),

                $this->mergeWhen($request->user() && $request->user()->isSecretariat(), [
                    'approve' => $status->value === ArticleStatusEnum::applyingPublish()->value,
                    'remand' => $status->value === ArticleStatusEnum::applyingPublish()->value,
                    'editApproval' =>  $status->value === ArticleStatusEnum::requestEdit()->value,
                    'deleteApproval' =>  $status->value === ArticleStatusEnum::requestDelete()->value,
                ])

            ]
        ];
    }
}
