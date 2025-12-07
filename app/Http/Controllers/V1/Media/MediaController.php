<?php

namespace App\Http\Controllers\V1\Media;

use App\Http\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, UserRepository $userRepo)
    {
        if ($request->type == 'avatar') {
            $user_id = $request->user_id;
            if ($user_id) {
                $media = $userRepo->avatar(userId: $user_id);

                return ApiResponse::success(
                    data: new UserResource($media)
                );
            }
        }
    }
}
