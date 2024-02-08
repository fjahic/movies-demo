<?php

namespace Models;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function genre_belongs_to_movies(): void
    {
        $genre = Genre::factory()->create();

        $this->assertInstanceOf(Collection::class, $genre->movies);
    }
}
