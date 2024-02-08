<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Http\Requests\ReviewUpdateRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Movie $movie): JsonResource
    {
        return ReviewResource::collection(
            Review::with('user')->where('movie_id', $movie->id)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewStoreRequest $request): JsonResource|JsonResponse
    {
        $exists = Review::where('user_id', Auth::id())
            ->where('movie_id', $request->get('movie_id'))
            ->exists();

        if ($exists) {
            return Response::json(['message' => trans('Review already exists.')], 422);
        }

        $review = Review::create(
            array_merge($request->validated(), ['user_id' => Auth::id()])
        );

        return new ReviewResource($review->load('movie'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review): JsonResource
    {
        return new ReviewResource($review->load('movie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReviewUpdateRequest $request, Review $review): JsonResource
    {
        $this->authorize('update', $review);
        $review->update($request->validated());

        return new ReviewResource($review->load('movie'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review): JsonResponse
    {
        $this->authorize('delete', $review);
        $review->delete();

        return $this->successResponse();
    }
}
