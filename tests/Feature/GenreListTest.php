<?php

namespace Tests\Feature;

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\AuthenticateApi;

class GenreListTest extends TestCase
{
    use AuthenticateApi;
    use RefreshDatabase;

    #[Test]
    public function user_can_view_list_of_available_genres(): void
    {
        Genre::factory()->count(10)->create();

        $response = $this->getJson(route('genres.index'), $this->getAuthorizationHeader());

        $response->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 10)->etc());
    }
}
