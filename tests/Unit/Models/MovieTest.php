<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function movie_has_reviews(): void
    {
        $movie = Movie::factory()->create();

        $this->assertInstanceOf(Collection::class, $movie->reviews);
    }

    #[Test]
    public function movie_belongs_to_genres(): void
    {
        $movie = Movie::factory()->create();

        $this->assertInstanceOf(Collection::class, $movie->genres);
    }

    #[Test]
    public function movie_has_favorites(): void
    {
        $movie = Movie::factory()->create();

        $this->assertInstanceOf(Collection::class, $movie->favorites);
    }

    #[Test]
    public function movie_has_follows(): void
    {
        $movie = Movie::factory()->create();

        $this->assertInstanceOf(Collection::class, $movie->follows);
    }
}
