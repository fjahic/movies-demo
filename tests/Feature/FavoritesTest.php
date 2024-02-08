<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\AuthenticateApi;

class FavoritesTest extends TestCase
{
    use AuthenticateApi;
    use RefreshDatabase;

    #[Test]
    public function user_can_add_a_movie_to_favorites(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $response = $this->post(
            route('favorites.store'),
            ['movie_id' => $movie->id], $this->getAuthorizationHeader($user));

        $response->assertSuccessful();
        $this->assertDatabaseHas('favorites', ['user_id' => $user->id, 'movie_id' => $movie->id]);
    }

    #[Test]
    public function user_can_view_a_list_of_own_favorites(): void
    {
        $user = User::factory()->create();
        $favorites = Favorite::factory()
            ->count(5)
            ->recycle($user)
            ->create();

        $response = $this->get(route('favorites.index'), $this->getAuthorizationHeader($user));

        $response
            ->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 5)->etc());
    }

    #[Test]
    public function user_can_delete_a_favorite(): void
    {
        $user = User::factory()->create();
        $favorite = Favorite::factory()
            ->recycle($user)
            ->create();

        $response = $this->deleteJson(
            route('favorites.destroy', $favorite->id),
            $this->getAuthorizationHeader($user),
        );

        $response->assertSuccessful();
        $this->assertModelMissing($favorite);
    }

    #[Test]
    public function user_cannot_delete_a_favorite_they_dont_own(): void
    {
        $user = User::factory()->create();
        $favorite = Favorite::factory()
            ->recycle($user)
            ->create();

        $response = $this->deleteJson(
            route('favorites.destroy', $favorite->id),
            $this->getAuthorizationHeader(User::factory()->create()),
        );

        $response->assertForbidden();
        $this->assertModelExists($favorite);
    }
}