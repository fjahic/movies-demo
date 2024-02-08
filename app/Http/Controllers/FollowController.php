<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\FollowStoreRequest;
use App\Http\Resources\FollowResource;
use App\Models\Follow;
use App\Models\Movie;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        return new JsonResource(
            Follow::where('user_id', Auth::id())
                ->with('movie')
                ->with('movie.reviews', fn($query) => $query->orderByDesc('updated_at')->limit(1))
                ->orderByDesc(
                    Review::select('updated_at')
                        ->whereColumn('movie_id', 'follows.movie_id')
                        ->orderByDesc('updated_at')
                        ->limit(1)
                )
                ->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FollowStoreRequest $request): JsonResource
    {
        $follow = Follow::firstOrCreate([
            'user_id' => Auth::id(),
            'movie_id' => $request->get('movie_id'),
        ]);

        return new FollowResource($follow);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Follow $follow)
    {
        $this->authorize('delete', $follow);

        $follow->delete();

        return $this->successResponse();
    }
}
