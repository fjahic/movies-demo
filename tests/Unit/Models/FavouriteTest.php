<?php

namespace Models;

use App\Models\Favorite;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FavouriteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function favorite_belongs_to_a_user(): void
    {
        $favorite = Favorite::factory()->create();

        $this->assertInstanceOf(User::class, $favorite->user);
    }

    #[Test]
    public function favorite_belongs_to_a_movie(): void
    {
        $favorite = Favorite::factory()->create();

        $this->assertInstanceOf(Movie::class, $favorite->movie);
    }
}
