<?php

namespace App\Modules\Articles\Services;

use App\Enums\ArticleStatusEnum;
use App\Enums\RevisedArticleEnum;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\ArticleCategoryResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\TopArticleResource;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Repositories\ArticleRepository;
use App\Modules\Articles\Repositories\TopArticleRepository;
use App\Modules\Company\Repositories\CategoryRepository;
use App\Repositories\MediaRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\ArticleCategoryFilter;
use App\Filters\ArticleDatePublishFilter;
use App\Filters\ArticleStatusFilter;
use App\Filters\ArticleSearchKeywordFilter;
use App\Http\Resources\PaginateResource;
use App\Modules\Articles\Models\ArticleCategory;
use App\Modules\Articles\Repositories\ArticleRevisedRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ArticleService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected ArticleRepository $articleRepository,
        protected MediaRepository $mediaRepository,
        protected TopArticleRepository $topArticleRepository,
        protected CategoryRepository $categoryRepository,
        protected ArticleRevisedRepository $articleRevisedRepository,
    ) {}


    public function topPage()
    {
        try {
            $data =  [
                "top_articles" =>  $this->topArticles(),
                "articles" =>  $this->articles(),
                "category" =>   $this->specialCategory(),
            ];
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success($data);
    }

    public function article(string $articleId)
    {
        try {
            $article = $this->articleRepository->find(
                $articleId,
                with: ['media', 'tags', 'user', 'categories', 'topArticle', 'organization', 'relatedArticleByTags', 'relatedArticleByCategories','alignment_medias'],
                scopes: ['published']
            );

            if (!$article) return ApiResponse::error(__("Article not found."), 404);
        } catch (\Throwable $th) {
            Log::error("Error Retreiving article: {$articleId} ::" . $th->getTraceAsString());
            return ApiResponse::error($th->getMessage());
        }
        return ApiResponse::success(new ArticleResource($article));
    }

    public function articles()
    {
        try {
            $articles = $this->articleRepository->articles();
        } catch (\Throwable $th) {
            Log::error('Error retrieving article: ' . $th->getTraceAsString());
            return ApiResponse::error($th->getMessage());
        }

        return ArticleResource::collection($articles);
    }

    public function topArticles()
    {
        try {
            $topArticles = $this->topArticleRepository->topArticles();
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return TopArticleResource::collection($topArticles);
    }

    public function specialCategory()
    {
        try {
            $specialCategory = $this->categoryRepository->all(
                with: ['articles' => function ($q) {
                    return $q->where('status', ArticleStatusEnum::published())
                        ->with(['media'])
                        ->latest()
                        ->published()
                        ->limit(Article::CATEGORY_ARTICLES_LIMIT);
                }],
                orderBy:'order',
                sortBy:'desc',
                where: [
                    ['special_flg', 1], 
                    ['name', '!=', ArticleCategory::EVENT_CATEGORY]
                ]
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ArticleCategoryResource::collection($specialCategory);
    }

    public function articleSearch()
    {
        try {
            $searchTerm = request('q', "");
            $searchPerPage = request('per_page', Article::LIMIT_PER_PAGE);
            $date_from = request('date_from');
            $date_to = request('date_to' , $date_from);
            
            $articles = $this->articleRepository->search(
                searchTerm: $searchTerm,
                per_page: $searchPerPage,
                date_from: $date_from,
                date_to: $date_to
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new PaginateResource($articles , ArticleResource::class));
    }

    public function create(array $data)
    {
        
        //Gate::authorize('create', Article::class);

        try {
            // Articles created by advertiser users will be 1 | true
            // if secretariat creates article, the provided pr_flag from request will be used.
            if (!Auth::user()->isSecretariat()) {
                $data['pr_flg'] = Auth::user()->isAdvertiser();
            }

            $article = $this->articleRepository->create($data);

             // if submit button, it will apply status to applyingPublish
             if (!empty($data['is_published']) && $data['is_published']) {
                $article->status = ArticleStatusEnum::applyingPublish()->value;
                $article->save();
            }

            if (request()->file('attachments')) {
                $this->mediaRepository->uploadMultiple(request()->file('attachments'), $article);
            }

            $categories = request('categories', []);
            $tags = request('tags', []);
            $alignment_medias = request('alignment_medias', []);

            if ($categories) {
                $this->articleRepository->assignCategories($categories, $article);
            }

            if ($tags) {
                $this->articleRepository->assignTags($tags, $article);
            }

            if ($alignment_medias) {
                $this->articleRepository->assignAlignmentMedias($alignment_medias, $article);
            }
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new ArticleResource($article));
    }

    public function update(array $data, string $id)
    {
        try {
            $article = $this->articleRepository->find(id: $id);

            //Gate::authorize('update', $article);

            if (!$article) return ApiResponse::error("Article not found.");

            $article->update($data);

            if (request()->file('attachments')) {
                $this->mediaRepository->uploadMultiple(request()->file('attachments'), $article);
            }

            $categories = request('categories', []);
            $tags = request('tags', []);

            if ($categories) {
                $this->articleRepository->assignCategories($categories, $article);
            }

            if ($tags) {
                $this->articleRepository->assignTags($tags, $article);
            }
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new ArticleResource($article));
    }

    /**
     * Count Articles for a user
     */
    public function countUserArticles()
    {
        $count = $this->articleRepository->countArticles();
        return ApiResponse::success($count);
    }

    public function delete(string $articleId)
    {

        try {
            $article = $this->articleRepository->find(id: $articleId);

            Gate::authorize('delete',  $article);

            $deleted = $article->delete();

            if ($deleted) {
                return ApiResponse::success('Article deleted successfully.');
            }

            return ApiResponse::error('Article could not be deleted.');
        } catch (\Throwable $th) {
            Log::error("Error deleting article with ID {$articleId}: " . $th->getMessage(), [
                'exception' => $th
            ]);
            return ApiResponse::error($th->getMessage());
        }
    }

    public function search()
    {
        try {

            $articles = $this->articleRepository->all(
                with: ['user', 'categories', 'topArticle','organization'],
                allowedFilters: [
                    AllowedFilter::custom('status', new ArticleStatusFilter),
                    AllowedFilter::custom('search', new ArticleSearchKeywordFilter),
                    AllowedFilter::custom('categories', new ArticleCategoryFilter),
                    AllowedFilter::custom('dates', new ArticleDatePublishFilter)
                ],
                orderBy:'updated_at',
                sortBy:'desc',
                paginate: true,
                perPage: request('per_page', Article::LIMIT_PER_PAGE)
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(PaginateResource::make($articles, ArticleResource::class));
    }

    public function status()
    {
        try {
            $statuses = ArticleStatusEnum::getAll();

            return ApiResponse::success($statuses);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }
    }

    public function getArticle(string $id)
    {
        try {
            $article = $this->articleRepository->find(
                id: $id,
                with: ['media', 'user', 'categories', 'tags', 'organization', 'remand', 
                'revised' => function($q) {
                    $q->with(['revisedArticleCategories' , 'revisedArticleTags','organization']);
                }, 
                'alignment_medias', 'topArticle']
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new ArticleResource($article));
    }

    public function save($articleId, array $data)
    {

        $isSubmit = request()->route()->getName() == 'articles.submit';

        try {
            $result = DB::transaction(function () use ($articleId,  $data, $isSubmit) {
                // get the article that has been revised
                // check if the current article status is published. If published,

                // - update the status to ArticleStatusEnum::requestEdit()
                // - and on the revised_article table request_type will be RevisedArticleEnum::correctionRequest()
                $article = $this->articleRepository->find(id: $articleId);

                Gate::authorize('update', $article);

                if (!$article) throw new Exception("Article " . __("not_found_common"));

                $this->applyArticleChanges($article, $data,  $isSubmit);

                $alignment_medias = request('alignment_medias', []);
                
                if ($alignment_medias) {
                    $this->articleRepository->assignAlignmentMedias($alignment_medias, $article);
                }

                return $article;
            });
        } catch (\Throwable $th) {
            info($th->getTraceAsString());
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new ArticleResource($result));
    }

    public function applyArticleChanges(Article $article, array $data, $isSubmit = false)
    {
        switch ($article->status) {
            case ArticleStatusEnum::published()->value:
                $article->update(['status' => ArticleStatusEnum::requestEdit()->value]);
                $this->revised($article, $data);
                break;

            case ArticleStatusEnum::requestEdit()->value:
                $this->revised($article, $data);
                break;

            default:
                $article->update([
                    ...$data,
                    'status' =>  $isSubmit ? ArticleStatusEnum::applyingPublish()->value : $article->status
                ]);
                if (request()->file('attachments')) {
                    $this->mediaRepository->uploadMultiple(request()->file('attachments'), $article);
                }
                break;
        }
    }

    public function revised(Article $article, array $data)
    {

        $requestType = isset($data['request_type']) && $data['request_type'] ?

            RevisedArticleEnum::from($data['request_type'])->value :

            RevisedArticleEnum::correctionRequest()->value;

        $articleRevised = $article->revised()->updateOrCreate(
            [
                "article_id" => $article->id,
                "user_id" => $article->user_id,
            ],
            [
                ...$data,
                "request_type" => $requestType
            ]
        );

        if (request()->file('attachments')) {
            $this->mediaRepository->uploadMultiple(request()->file('attachments'), $articleRevised);
        }

        $categories = request('categories', []);
        $tags = request('tags', []);
        $alignment_medias = request('alignment-medias', []);

        if ($categories) {
            $this->articleRevisedRepository->revisedArticleCategories($categories, $articleRevised);
        }

        if ($tags) {
            $this->articleRevisedRepository->revisedArticleTags($tags, $articleRevised);
        }
    }

    public function deleteRequest(string $id)
    {

        try {
            $article = $this->articleRepository->find(id: $id);

            if (!$article) throw new Exception("Article " . __("not_found_common"));

            $article->update(['status' => ArticleStatusEnum::requestDelete()->value]);

            $article->revised()->updateOrCreate([
                'article_id' => $article->id,
                'user_id' => $article->user_id
            ], [
                'request_type' => RevisedArticleEnum::deletionRequest()->value,
                'comment' => request('comment', "")
            ]);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }

    public function withdrawalOfRequest(string $id)
    {
        try {
            $article = $this->articleRepository->find(id: $id);

            if (!$article) throw new Exception("Article " . __("not_found_common"));

            $article->revised()?->delete();

            $article->update(['status' => ArticleStatusEnum::published()->value]);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }
}
