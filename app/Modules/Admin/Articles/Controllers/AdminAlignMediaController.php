<?php

namespace App\Modules\Admin\Articles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Company\Repositories\AlignMediaRepository;
use Illuminate\Http\Request;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\AlignMediaResource;
use App\Http\Requests\AlignmentMediaRequest;
use App\Modules\Admin\Articles\Services\AlignmentMediaService;
use App\Modules\Articles\Models\Article;

class AdminAlignMediaController extends Controller
{
    public function __construct(
        protected AlignMediaRepository $alignMediaRepository,
        protected AlignmentMediaService $alignmentMediaService
    ) {}

    public function index(Request $request)
    {
        $tags = $this->alignMediaRepository->all(
            with: ['articles'],
            orderBy: 'updated_at',
            sortBy: 'desc',
        );

        return ApiResponse::success(AlignMediaResource::collection($tags));
    }

    public function show(string $id)
    {

        try {
            $alignment_media = $this->alignMediaRepository->find(id: $id);
            if (!$alignment_media) return ApiResponse::error(__("align_media_not_found"), 404);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new AlignMediaResource($alignment_media));
    }

    public function store(AlignmentMediaRequest $request)
    {
        $data = $request->validated();
        return $this->alignmentMediaService->create($data);
    }

    public function update(string $id, AlignmentMediaRequest $request)
    {
        $data = $request->validated();
        return $this->alignmentMediaService->update($id, $data);
    }

    public function delete(string $id)
    {
        return $this->alignmentMediaService->delete($id);
    }
}
