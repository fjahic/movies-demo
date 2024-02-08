<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\FavouriteStoreRequest;
use App\Http\Resources\FavoriteResource;
use App\Http\Traits\HasPaginatedCache;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class FavoriteController extends Controller
{
    use HasPaginatedCache;

    private int $perPage = 15;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResource
    {
        $key = $this->getPageCacheKey('favorites', $request->get('page', 1));

        return Cache::remember($key, Config::get('cache.ttl'), function () {
            return FavoriteResource::collection(
                Favorite::where('user_id', Auth::id())->paginate($this->perPage)
            );
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FavouriteStoreRequest $request): JsonResource
    {
        $favorite = Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'movie_id' => $request->get('movie_id'),
        ]);
        $this->forgetPageCaches('favorites', $this->getPagesCount());

        return new FavoriteResource($favorite);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite): JsonResponse
    {
        $this->authorize('delete', $favorite);

        $favorite->delete();
        $this->forgetPageCaches('favorites', $this->getPagesCount());

        return $this->successResponse();
    }

    private function getPagesCount(): int
    {
        $total = Favorite::where('user_id', Auth::id())->count();

        return (int)ceil($total / $this->perPage);
    }
}
