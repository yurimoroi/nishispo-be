<?php

namespace App\Modules\Company\Controllers;

use App\Enums\ArticleEnum;
use App\Enums\ArticleStatusEnum;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Modules\Articles\Models\Article;
use App\Modules\Company\Repositories\TagRepository;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(
        protected TagRepository $tagRepo
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', Article::LIMIT_PER_PAGE);

        $tags = $this->tagRepo->all(
            whereHas: [
                'articles' => [
                    'status' => ArticleStatusEnum::published()->value
                ]
            ],
            limit: $limit
        );

        return ApiResponse::success(TagResource::collection($tags));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $per_page = request()->get('per_page', Article::LIMIT_PER_PAGE);
        try {
            $tag = $this->tagRepo->find($id);
            if (!$tag) return ApiResponse::error(__("tag_not_found"), 404);

            $tag_articles = $tag->articles()->published()->paginate($per_page);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(['articles' => $tag_articles, 'tag' => $tag]);  
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
