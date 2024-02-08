<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Follow;
use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\AuthenticateApi;

class FollowingTest extends TestCase
{
    use AuthenticateApi;
    use RefreshDatabase;

    #[Test]
    public function user_can_follow_a_movie(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $response = $this->postJson(
            route('follows.store', ['movie_id' => $movie->id]),
            $this->getAuthorizationHeader($user),
        );

        $response->assertSuccessful();
        $this->assertDatabaseHas('follows', ['user_id' => $user->id, 'movie_id' => $movie->id]);
    }

    #[Test]
    public function user_can_view_a_list_of_movies_they_follow(): void
    {
        $user = User::factory()->create();

        Review::factory()
            ->count(10)
            ->create();

        Follow::factory()
            ->count(5)
            ->recycle($user)
            ->sequence(fn(Sequence $sequence) => ['movie_id' => $sequence->index + 1])
            ->create();

        $response = $this->getJson(route('follows.index'), $this->getAuthorizationHeader($user));

        $response
            ->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 5)->etc());
    }

    #[Test]
    public function user_can_unfollow_a_movie(): void
    {
        $user = User::factory()->create();
        $follow = Follow::factory()
            ->recycle($user)
            ->create();

        $response = $this->deleteJson(
            route('follows.destroy', $follow),
            $this->getAuthorizationHeader($user),
        );

        $response->assertSuccessful();
        $this->assertModelMissing($follow);
    }

    #[Test]
    public function user_cannot_unfollow_a_movie_from_another_user(): void
    {
        $user = User::factory()->create();
        $follow = Follow::factory()
            ->recycle($user)
            ->create();

        $response = $this->deleteJson(
            route('follows.destroy', $follow),
            $this->getAuthorizationHeader(User::factory()->create()),
        );

        $response->assertForbidden();
        $this->assertModelExists($follow);
    }
}
