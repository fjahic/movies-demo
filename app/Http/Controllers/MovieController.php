<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\InvalidFilterException;
use App\Http\Filters\FilterBuilder;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws InvalidFilterException
     */
    public function index(Request $request): JsonResource
    {
        $query = Movie::with('genres');

        if ($request->has('filter')) {
            (new FilterBuilder($query))->apply($request->get('filter'));
        }

        return MovieResource::collection($query->paginate());
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie): JsonResource
    {
        return new MovieResource($movie->load(['genres', 'latestReviews']));
    }
}
