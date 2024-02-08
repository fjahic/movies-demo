<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\AuthenticateApi;

class ReviewsTest extends TestCase
{
    use AuthenticateApi;
    use RefreshDatabase;

    #[Test]
    public function user_can_view_a_list_of_all_movie_reviews(): void
    {
        $movie = Movie::factory()->create();
        $reviews = Review::factory()
            ->for($movie)
            ->count(3)
            ->create();

        $response = $this->get(
            route('movies.reviews.index', $movie->slug),
            $this->getAuthorizationHeader(),
        );

        $response
            ->assertSuccessful()
            ->assertJson(fn(AssertableJson $json): AssertableJson
                => $json->has('data', 3, fn(AssertableJson $json)
                    => $json->where('title', $reviews->first()->title)->etc()
                )->etc()
            );
    }

    #[Test]
    public function user_can_view_a_lists_of_their_reviews(): void
    {
        $user = User::factory()->create();
        Review::factory()
            ->count(5)
            ->for($user)
            ->create();

        $response = $this->get(route('reviews.index'), $this->getAuthorizationHeader($user));

        $response->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 5)->etc());
    }

    #[Test]
    public function user_can_post_a_review(): void
    {
        $movie = Movie::factory()->create();
        $reviewData = [
            'movie_id' => $movie->id,
            'title' => fake()->sentence,
            'body' => fake()->paragraph,
            'rating' => fake()->numberBetween(1, 10),
        ];

        $response = $this->post(
            route('movies.reviews.store', $movie->slug),
            $reviewData,
            $this->getAuthorizationHeader(),
        );

        $response->assertSuccessful();
        $this->assertDatabaseHas('reviews', $reviewData);
    }

    #[Test]
    public function user_can_view_any_single_review(): void
    {
        $review = Review::factory()
            ->for(User::factory())
            ->for(Movie::factory())
            ->create();

        $response = $this->get(
            route('reviews.show', $review->id),
            $this->getAuthorizationHeader(),
        );

        $response->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json->where('data.title', $review->title)->etc());
    }

    #[Test]
    public function user_can_update_own_review(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();
        $review = Review::factory()
            ->for($user)
            ->for($movie)
            ->create();

        $original = $review->only(['id', 'body', 'rating']);
        $updated = [
            'body' => fake()->paragraph,
            'rating' => fake()->numberBetween(1, 10),
        ];

        $response = $this->patchJson(
            route('reviews.update', $review->id),
            $updated,
            $this->getAuthorizationHeader($user),
        );

        $response->assertSuccessful();
        $this->assertDatabaseMissing('reviews', $original)
            ->assertDatabaseHas('reviews', $updated);
    }

    #[Test]
    public function user_cannot_update_reviews_that_dont_belong_to_them(): void
    {
        $movie = Movie::factory()->create();
        $review = Review::factory()
            ->for(User::factory()->create())
            ->for($movie)
            ->create();

        $original = $review->only(['id', 'body', 'rating']);
        $updated = [
            'body' => fake()->paragraph,
            'rating' => fake()->numberBetween(1, 10),
        ];

        $response = $this->patchJson(
            route('reviews.update', $review->id),
            $updated, $this->getAuthorizationHeader(),
        );

        $response->assertForbidden();
        $this->assertDatabaseMissing('reviews', $updated)
            ->assertDatabaseHas('reviews', $original);
    }

    #[Test]
    public function user_can_delete_own_review(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();
        $review = Review::factory()
            ->for($user)
            ->for($movie)
            ->create();

        $response = $this->deleteJson(
            route('reviews.destroy', $review->id),
            $this->getAuthorizationHeader($user),
        );

        $response->assertSuccessful();
        $this->assertModelMissing($review);
    }

    #[Test]
    public function user_cannot_delete_reviews_that_dont_belong_to_them(): void
    {
        $movie = Movie::factory()->create();
        $review = Review::factory()
            ->for(User::factory()->create())
            ->for($movie)
            ->create();

        $response = $this->deleteJson(
            route('reviews.destroy', $review->id),
            $this->getAuthorizationHeader(),
    );

        $response->assertForbidden();
        $this->assertModelExists($review);
    }
}