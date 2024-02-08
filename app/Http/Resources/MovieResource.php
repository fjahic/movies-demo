<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'storyline' => $this->storyline,
            'director' => $this->director,
            'release_year' => $this->release_year,
            'slug' => $this->slug,
            'rating' => $this->rating,
            'genres' => GenreResource::collection($this->whenLoaded('genres')),
            'latest_reviews' => ReviewResource::collection(
                $this->whenLoaded('latestReviews'),
            )
        ];
    }
}
