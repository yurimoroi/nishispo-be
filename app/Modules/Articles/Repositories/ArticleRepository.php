<?php

namespace App\Modules\Articles\Repositories;

use App\Enums\ArticleEnum;
use App\Enums\ArticleStatusEnum;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\RevisedArticle;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */

    public function __construct(Article $model)
    {
        parent::__construct($model);
    }

    public function articles()
    {
        return $this->model
            ->has('user')
            ->published()
            ->whereDoesntHave('categories', function ($query) {
                $query->where('name', '研修');
            })
            ->with([
                'media',
                'user',
                'categories'
            ])
            ->orderBy('created_at', 'desc')
            ->limit(Article::TOP_PAGE_ARTICLE_LIMIT)
            ->get();
    }

    public function search($searchTerm = null, $per_page = Article::LIMIT_PER_PAGE, $date_from = null, $date_to = null)
    {
        // Search by keyword, tag, writer, category, or event date

        $searchResults = $this->model
            ->with(['categories', 'tags', 'user'])
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->orWhere('body', 'like', '%' . $searchTerm . '%')
                    ->orWhere('title', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('tags', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('user', function ($query) use ($searchTerm) {
                        $query->where('contributor_name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('categories', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereDate('updated_at', $searchTerm);
            })
            ->when($date_from && $date_to, function ($query) use ($date_from, $date_to) {
                $date_from = Carbon::parse($date_from)->startOfDay();
                $date_to = Carbon::parse($date_to)->endOfDay();

                $query->whereBetween('published_at', [$date_from, $date_to]);
            })
            ->published()
            ->paginate($per_page);

        return  $searchResults;
    }

    public function countArticles()
    {
        return DB::table($this->model->getTable())->selectRaw("
            COUNT(*) as count,
            SUM(CASE WHEN status = " . ArticleStatusEnum::draft() . " THEN 1 ELSE 0 END) as draft,
            SUM(CASE WHEN status = " . ArticleStatusEnum::applyingPublish() . " THEN 1 ELSE 0 END) as applying_publish,
            SUM(CASE WHEN status = " . ArticleStatusEnum::published() . " THEN 1 ELSE 0 END) as published,
            SUM(CASE WHEN status = " . ArticleStatusEnum::remand() . " THEN 1 ELSE 0 END) as remand,
            SUM(CASE WHEN status = " . ArticleStatusEnum::applyingRemand() . " THEN 1 ELSE 0 END) as applying_remand,
            SUM(CASE WHEN status = " . ArticleStatusEnum::requestEdit() . " THEN 1 ELSE 0 END) as request_edit,
            SUM(CASE WHEN status = " . ArticleStatusEnum::requestDelete() . " THEN 1 ELSE 0 END) as request_delete
            ")
            ->where('user_id', Auth::user()->id)
            ->whereNull('deleted_at')
            ->first();
    }

    public function assignCategories(array $categories, Article $article)
    {
        $syncCategories = array_map(fn($category) => [
            "id" => Str::ulid(),
            "article_id" => $article->id,
            "article_category_id" => $category,
        ], $categories);

        $article->categories()->sync($syncCategories);
    }

    public function assignTags(array $tags, Article $article)
    {
        $syncTags = array_map(fn($tag) => [
            "id" =>  Str::ulid(),
            "article_id" => $article->id,
            "article_tag_id" => $tag
        ], $tags);

        $article->tags()->sync($syncTags);
    }

    public function assignAlignmentMedias(array $alignment_medias, Article $article)
    {
        foreach ($alignment_medias as $alignment_media) {
            $syncAlignmentMedias[] = [
                "id" =>  Str::ulid(),
                "alignment_media_id" => $alignment_media,
                "article_id" => $article->id
            ];
        }

        $article->alignment_medias()->sync($syncAlignmentMedias);
    }

    public function remand(Article $article, array $data)
    {
        $article->remand()->updateOrCreate(
            ['article_id' => $article->id],
            $data
        );
    }

    public function approved(Article $article)
    {
        DB::transaction(function () use ($article) {
            $article->update(['status' => ArticleStatusEnum::published()->value]);
            $this->removeRemandRevised($article);
        });
    }

    public function editApproved(Article $article, RevisedArticle $revisedArticle)
    {
        DB::transaction(function () use ($article, $revisedArticle) {
            $newArticleData = [
                'user_id' => $revisedArticle->user_id,
                'title' => $revisedArticle->title,
                'body' => $revisedArticle->body,
                'organization_id' => $revisedArticle->organization_id,
                'published_at' => $revisedArticle->published_at,
                'publish_ended_at' => $revisedArticle->publish_ended_at
            ];

            $article->update([...$newArticleData, 'status' => ArticleStatusEnum::published()->value]);

            $categories = $revisedArticle->revisedArticleCategories()->pluck('article_category_id');
            $tags = $revisedArticle->revisedArticleTags()->pluck('article_tag_id');

            $this->assignCategories($categories->toArray(), $article);
            $this->assignTags($tags->toArray(), $article);
            $this->removeRemandRevised($article, $revisedArticle);
        });
    }

    public function deleteApproved(Article $article)
    {
        $article->delete();
        $article->remand()?->delete();
        $article->revised()?->delete();
    }

    public function removeRemandRevised(Article $article)
    {
        $article->remand()?->delete();
        $article->revised()?->delete();
    }
}
