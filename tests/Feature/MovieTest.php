<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\AuthenticateApi;

class MovieTest extends TestCase
{
    use AuthenticateApi;
    use RefreshDatabase;

    #[Test]
    public function user_can_view_a_list_of_movies(): void
    {
        $movies = Movie::factory(16)->create();

        $response = $this
            ->get(route('movies.index'), $this->getAuthorizationHeader());

        $response
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($movies) {
                return $json->has('data.0', fn(AssertableJson $json)
                        => $json->where('title', $movies->first()->title)->etc()
                    )
                    ->has('links')
                    ->etc();
            });
    }

    #[Test]
    public function user_can_filter_a_list_of_movies_by_release_year(): void
    {
        Movie::factory()
            ->count(10)
            ->sequence(function (Sequence $sequence) {
                if ($sequence->index < 5) {
                    return ['release_year' => 2010];
                }

                return ['release_year' => fake()->year(2009)];
            })
            ->create();

        $response = $this->get(
            route('movies.index', ['filter[release_year]' => 2010]),
            $this->getAuthorizationHeader()
        );

        $response->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 5)->etc());
    }

    #[Test]
    public function user_can_filter_a_list_of_movies_by_genre(): void
    {
        Genre::factory()
            ->count(10)
            ->sequence(function (Sequence $sequence) {
                if ($sequence->index < 5) {
                    return ['name' => 'Programming Fiction'];
                }

                return [];
            })
            ->has(Movie::factory())
            ->create();

        $response = $this->get(
            route('movies.index', ['filter[genre]' => 'Programming Fiction']),
            $this->getAuthorizationHeader(),
        );

        $response->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 5)->etc());
    }

    #[Test]
    public function user_can_view_a_single_movie(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->get(route('movies.show', $movie->slug), $this->getAuthorizationHeader());

        $response->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json->where('data.title', $movie->title)->etc());
    }
}
